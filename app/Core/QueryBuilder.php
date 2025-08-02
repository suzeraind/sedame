<?php

namespace App\Core;

use PDO;

class QueryBuilder
{
    /**
     * @var string
     */
    protected string $table = '';

    /**
     * @var array<int, string>
     */
    protected array $conditions = [];

    /**
     * @var array<int, mixed>
     */
    protected array $bindings = [];

    /**
     * @var string
     */
    protected string $select = '*';

    /**
     * @var int
     */
    protected int $limit = 10;

    /**
     * @var string
     */
    protected string $orderBy = 'created_at';

    /**
     * @var array<int, string>
     */
    protected array $joins = [];

    /**
     * @var PDO|null
     */
    protected ?PDO $pdo;

    /**
     * QueryBuilder constructor.
     *
     * @param PDO|null $pdo
     */
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance()->getPdo();
    }

    /**
     * Set the table for the query.
     *
     * @param string $table
     * @return $this
     */
    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set the columns to select.
     *
     * @param string|array<int, string> $columns
     * @return $this
     */
    public function select(string|array $columns = '*'): self
    {
        $this->select = is_array($columns) ? implode(', ', $columns) : $columns;
        return $this;
    }

    /**
     * Add a where condition to the query.
     *
     * @param string $column
     * @param mixed $operator
     * @param mixed|null $value
     * @return $this
     */
    public function where(string $column, mixed $operator, mixed $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->conditions[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    /**
     * Add a whereIn condition to the query.
     *
     * @param string $column
     * @param array<int, mixed> $values
     * @return $this
     */
    public function whereIn(string $column, array $values): self
    {
        if (empty($values)) {
            return $this;
        }
        $placeholders = str_repeat('?,', count($values) - 1) . '?';
        $this->conditions[] = "{$column} IN ($placeholders)";
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }

    /**
     * Set the order by clause for the query.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy = "{$column} {$direction}";
        return $this;
    }

    /**
     * Add a join clause to the query.
     *
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @param string $type
     * @return $this
     */
    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self
    {
        $this->joins[] = "{$type} JOIN {$table} ON {$first} {$operator} {$second}";
        return $this;
    }

    /**
     * Get the first result of the query.
     *
     * @return mixed
     */
    public function first(): mixed
    {
        $this->limit(1);
        $result = $this->get();
        return $result ? $result[0] : null;
    }

    /**
     * Set the limit for the query.
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Get the results of the query.
     *
     * @return array<int, mixed>|false
     */
    public function get(): array|false
    {
        $sql = "SELECT {$this->select} FROM {$this->table}";

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        if ($this->orderBy) {
            $sql .= " ORDER BY {$this->orderBy}";
        }

        if ($this->limit) {
            $sql .= " LIMIT {$this->limit}";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);

        return $stmt->fetchAll();
    }

    /**
     * Get the count of the results of the query.
     *
     * @return int
     */
    public function count(): int
    {
        $this->select('COUNT(*) as count');
        $result = $this->first();
        return $result ? (int)$result['count'] : 0;
    }

    /**
     * Insert a new record into the table.
     *
     * @param array<string, mixed> $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Update a record in the table.
     *
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $setParts = [];
        $bindings = [];

        foreach ($data as $column => $value) {
            $setParts[] = "$column = ?";
            $bindings[] = $value;
        }
        $setClause = implode(', ', $setParts);

        $sql = "UPDATE {$this->table} SET {$setClause}";

        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
            $bindings = array_merge($bindings, $this->bindings);
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($bindings);
    }

    /**
     * Delete a record from the table.
     *
     * @return bool
     */
    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($this->bindings);
    }
}