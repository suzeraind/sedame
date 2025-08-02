<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Exception;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $model;
    private array $createdUsers = [];

    public function test_can_create_user(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => $this->generateUniqueEmail(),
            'password' => password_hash('testpass', PASSWORD_DEFAULT),
            'active' => 1
        ];

        $user = $this->model->create($userData);

        $this->assertIsArray($user);
        $this->assertArrayHasKey('id', $user);
        $this->assertEquals('Test User', $user['name']);
        $this->assertEquals($userData['email'], $user['email']);
        $this->assertEquals(1, $user['active']);

        $this->createdUsers[] = $user['id'];
    }

    private function generateUniqueEmail(): string
    {
        return 'test_' . uniqid() . '_' . time() . '@example.com';
    }

    public function test_can_find_user_by_id(): void
    {
        $user = $this->createUser([
            'name' => 'Find Test User',
            'email' => $this->generateUniqueEmail()
        ]);

        $userId = $user['id'];

        $foundUser = $this->model->find($userId);

        $this->assertIsArray($foundUser);
        $this->assertEquals($userId, $foundUser['id']);
        $this->assertEquals('Find Test User', $foundUser['name']);
    }

    private function createUser(array $data = []): array
    {
        $defaultData = [
            'name' => 'Test User',
            'email' => $this->generateUniqueEmail(),
            'password' => password_hash('testpass', PASSWORD_DEFAULT),
            'active' => 1
        ];

        $userData = array_merge($defaultData, $data);
        $user = $this->model->create($userData);

        if ($user) {
            $this->createdUsers[] = $user['id'];
        }

        return $user;
    }

    public function test_can_update_user(): void
    {
        $user = $this->createUser([
            'name' => 'Original Name',
            'email' => $this->generateUniqueEmail()
        ]);


        $updatedData = [
            'name' => 'Updated Name',
            'active' => 0
        ];

        $updatedUser = $this->model->update($user['id'], $updatedData);

        $this->assertIsArray($updatedUser);
        $this->assertEquals($user['id'], $updatedUser['id']);
        $this->assertEquals('Updated Name', $updatedUser['name']);
        $this->assertEquals(0, $updatedUser['active']);
    }

    public function test_can_delete_user(): void
    {
        $user = $this->createUser([
            'name' => 'Delete Test User',
            'email' => $this->generateUniqueEmail()
        ]);

        $userId = $user['id'];

        $deleted = $this->model->delete($userId);

        $this->assertTrue($deleted);

        $foundUser = $this->model->find($userId);
        $this->assertNull($foundUser);
    }

    public function test_can_get_all_users(): void
    {
        $users = $this->model->all();

        $this->assertIsArray($users);
        if (!empty($users)) {
            $this->assertIsArray($users[0]);
        }
    }

    public function test_can_find_user_by_email(): void
    {
        $uniqueEmail = $this->generateUniqueEmail();
        $this->createUser([
            'name' => 'Email Test User',
            'email' => $uniqueEmail
        ]);

        $foundUser = $this->model->findByEmail($uniqueEmail);

        $this->assertIsArray($foundUser);
        $this->assertEquals($uniqueEmail, $foundUser['email']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = User::inst();
    }

    protected function tearDown(): void
    {
        foreach ($this->createdUsers as $userId) {
            try {
                $this->model->delete($userId);
            } catch (Exception $e) {
            }
        }
        $this->createdUsers = [];

        parent::tearDown();
    }
}
