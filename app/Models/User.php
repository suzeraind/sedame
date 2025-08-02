<?php

namespace App\Models;

use App\Core\Model;
use App\Core\QueryBuilder;

class User extends Model
{
    /**
     * @var string
     */
    protected string $table = 'users';

    /**
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * Get all active users.
     *
     * @return QueryBuilder
     */
    public function active(): QueryBuilder
    {
        return $this->where('active', '=', 1);
    }

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return mixed
     */
    public function findByEmail(string $email): mixed
    {
        return $this->firstWhere('email', '=', $email);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @return array<int, string>
     */
    protected function fillable(): array
    {
        return ['name', 'email', 'password', 'active'];
    }
}
