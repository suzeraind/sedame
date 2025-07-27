<?php

namespace App\Core;

abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';

    public function all()
    {
        return $this->query()->get();
    }

    public function query()
    {
        return new QueryBuilder()->table($this->table);
    }

    public function firstWhere($column, $operator, $value = null)
    {
        return $this->where($column, $operator, $value)->first();
    }

    public function where($column, $operator, $value = null)
    {
        return $this->query()->where($column, $operator, $value);
    }

    public function create(array $data)
    {
        $fillable = $this->fillable();
        $filteredData = array_intersect_key($data, array_flip($fillable));

        $result = $this->query()->insert($filteredData);

        if ($result) {
            return $this->find($this->getLastInsertId());
        }

        return false;
    }

    abstract protected function fillable();

    public function find($id)
    {
        return $this->query()
            ->where($this->primaryKey, '=', $id)
            ->first();
    }

    protected function getLastInsertId()
    {
        return Database::getInstance()->getPdo()->lastInsertId();
    }

    public function update($id, array $data)
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

    public function delete($id)
    {
        return $this->query()
            ->where($this->primaryKey, '=', $id)
            ->delete();
    }
}
