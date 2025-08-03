<?php

namespace App\Core;

abstract class Model
{
    /**
     * @var string
     */
    protected string $table = '';

    /**
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * @return static
     */
    public static function inst(): static
    {
        return new static();
    }

    /**
     * @return array<int, mixed>|false
     */
    public function all(): array|false
    {
        return $this->query()->get();
    }

    /**
     * @return QueryBuilder
     */
    public function query(): QueryBuilder
    {
        return new QueryBuilder()->table($this->table);
    }

    /**
     * @param string $column
     * @param mixed $operator
     * @param mixed|null $value
     * @return mixed
     */
    public function firstWhere(string $column, mixed $operator, mixed $value = null): mixed
    {
        return $this->where($column, $operator, $value)->first();
    }

    /**
     * @param string $column
     * @param mixed $operator
     * @param mixed|null $value
     * @return QueryBuilder
     */
    public function where(string $column, mixed $operator, mixed $value = null): QueryBuilder
    {
        return $this->query()->where($column, $operator, $value);
    }

    /**
     * @param array<string, mixed> $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        $fillable = $this->fillable();
        $filteredData = array_intersect_key($data, array_flip($fillable));

        $result = $this->query()->insert($filteredData);

        if ($result) {
            $lastInsertId = $this->getLastInsertId();
            if ($lastInsertId !== false) {
                return $this->find($lastInsertId);
            }
        }

        return false;
    }

    /**
     * @return array<int, string>
     */
    abstract protected function fillable(): array;

    /**
     * @param mixed $id
     * @return mixed
     */
    public function find(mixed $id): mixed
    {
        return $this->query()
            ->where($this->primaryKey, '=', $id)
            ->first();
    }

    /**
     * @return string|false
     */
    protected function getLastInsertId(): string|false
    {
        return Database::getInstance()->getPdo()->lastInsertId();
    }

    /**
     * @param mixed $id
     * @param array<string, mixed> $data
     * @return mixed
     */
    public function update(mixed $id, array $data): mixed
    {
        $fillable = $this->fillable();
        $filteredData = array_intersect_key($data, array_flip($fillable));

        $result = $this->query()
            ->where($this->primaryKey, '=', $id)
            ->update($filteredData);

        if ($result) {
            return $this->find($id);
        }

        return false;
    }

    /**
     * @param mixed $id
     * @return bool
     */
    public function delete(mixed $id): bool
    {
        return $this->query()
            ->where($this->primaryKey, '=', $id)
            ->delete();
    }
}

