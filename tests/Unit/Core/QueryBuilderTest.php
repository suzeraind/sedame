<?php

namespace Tests\Unit\Core;

use PDO;
use App\Models\User;
use App\Core\Database;
use App\Core\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    private QueryBuilder $queryBuilder;
    private PDO $pdo;
    private array $createdUserIds = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdo = Database::getInstance()->getPdo();
        $this->queryBuilder = new QueryBuilder($this->pdo);
    }

    protected function tearDown(): void
    {
        $model = new User;
        foreach ($this->createdUserIds as $id) {
            $model->delete($id);
        }
        $this->createdUserIds = [];
        parent::tearDown();
    }

    public function test_can_insert_data(): void
    {
        $uniqueEmail = 'john.doe.' . uniqid() . '@example.com';
        $data = [
            'name' => 'John Doe',
            'email' => $uniqueEmail,
            'password' => 'secret',
            'active' => 1
        ];

        $result = $this->queryBuilder->table('users')->insert($data);
        $this->assertTrue($result);
        $this->createdUserIds[] = $this->pdo->lastInsertId();

        $user = $this->queryBuilder->table('users')->where('email', '=', $uniqueEmail)->first();
        $this->assertIsArray($user);
        $this->assertEquals('John Doe', $user['name']);
    }

    public function test_can_update_data(): void
    {
        $uniqueEmail = 'jane.doe.' . uniqid() . '@example.com';
        $this->queryBuilder->table('users')->insert([
            'name' => 'Jane Doe',
            'email' => $uniqueEmail,
            'password' => 'secret',
            'active' => 1
        ]);
        $this->createdUserIds[] = $this->pdo->lastInsertId();

        $updated = $this->queryBuilder
            ->table('users')
            ->where('email', '=', $uniqueEmail)
            ->update(['name' => 'Jane Smith']);

        $this->assertTrue($updated);

        $user = $this->queryBuilder->table('users')->where('email', '=', $uniqueEmail)->first();
        $this->assertEquals('Jane Smith', $user['name']);
    }

    public function test_can_delete_data(): void
    {
        $uniqueEmail = 'delete.me.' . uniqid() . '@example.com';
        $this->queryBuilder->table('users')->insert([
            'name' => 'User ToDelete',
            'email' => $uniqueEmail,
            'password' => 'secret',
            'active' => 1
        ]);
        $this->createdUserIds[] = $this->pdo->lastInsertId();
        $deleted = $this->queryBuilder
            ->table('users')
            ->where('email', '=', $uniqueEmail)
            ->delete();

        $this->assertTrue($deleted);

        $user = $this->queryBuilder->table('users')->where('email', '=', $uniqueEmail)->first();
        $this->assertNull($user);
    }

    public function test_can_select_with_where_clause(): void
    {
        $uniqueEmail1 = 'select.user.' . uniqid() . '@example.com';
        $this->queryBuilder->table('users')->insert([
            'name' => 'Select User',
            'email' => $uniqueEmail1,
            'password' => 'secret',
            'active' => 1
        ]);
        $this->createdUserIds[] = $this->pdo->lastInsertId();

        $uniqueEmail2 = 'another.user.' . uniqid() . '@example.com';
        $this->queryBuilder->table('users')->insert([
            'name' => 'Another User',
            'email' => $uniqueEmail2,
            'password' => 'secret',
            'active' => 0
        ]);
        $this->createdUserIds[] = $this->pdo->lastInsertId();

        $user = $this->queryBuilder
            ->table('users')
            ->where('email', '=', $uniqueEmail1)
            ->first();

        $this->assertNotNull($user);
        $this->assertEquals('Select User', $user['name']);
    }

    public function test_can_use_where_in(): void
    {
        $emailA = 'a.' . uniqid() . '@ex.com';
        $this->queryBuilder->table('users')->insert(['name' => 'User A', 'email' => $emailA, 'password' => 'p']);
        $this->createdUserIds[] = $this->pdo->lastInsertId();

        $emailB = 'b.' . uniqid() . '@ex.com';
        $this->queryBuilder->table('users')->insert(['name' => 'User B', 'email' => $emailB, 'password' => 'p']);
        $this->createdUserIds[] = $this->pdo->lastInsertId();

        $emailC = 'c.' . uniqid() . '@ex.com';
        $this->queryBuilder->table('users')->insert(['name' => 'User C', 'email' => $emailC, 'password' => 'p']);
        $this->createdUserIds[] = $this->pdo->lastInsertId();

        $users = $this->queryBuilder
            ->table('users')
            ->whereIn('email', [$emailA, $emailC])
            ->get();

        $this->assertCount(2, $users);
        $this->assertEquals('User A', $users[0]['name']);
        $this->assertEquals('User C', $users[1]['name']);
    }
}
