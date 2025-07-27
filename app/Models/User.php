<?php

namespace App\Models;

use App\Core\Model;
use App\Core\QueryBuilder;

class User extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';

    /**
     * @return QueryBuilder
     */
    public function active(): QueryBuilder
    {
        return $this->where('active', '=', 1);
    }


    /**
     * @param string $email
     * @return mixed|null
     */
    public function findByEmail(string $email): mixed
    {
        return $this->firstWhere('email', '=', $email);
    }

    /**
     * @return string[]
     */
    protected function fillable(): array
    {
        return ['name', 'email', 'password', 'active'];
    }
}