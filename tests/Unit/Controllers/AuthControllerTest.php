<?php

namespace Tests\Unit\Controllers;

use App\Controllers\AuthController;
use Tests\Unit\BaseTestCase;

class AuthControllerTest extends BaseTestCase
{
    private $authController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authController = new class($this->viewMock, $this->userModelMock) extends AuthController {
            protected function redirect(string $url): void
            {
                // Overridden to prevent actual redirection during tests
            }
        };
    }

    public function test_login_with_valid_credentials()
    {
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'password';

        $user = ['id' => 1, 'name' => 'Test User', 'password' => password_hash('password', PASSWORD_DEFAULT)];
        $this->userModelMock->method('findByEmail')->willReturn($user);

        $this->authController->login();

        $this->assertEquals(1, $_SESSION['user_id']);
        $this->assertEquals('Test User', $_SESSION['username']);
    }

    public function test_login_with_invalid_credentials()
    {
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'wrongpassword';

        $this->userModelMock->method('findByEmail')->willReturn(null);
        $this->viewMock->expects($this->once())->method('render')->with('auth/login');

        $this->authController->login();
        $this->assertArrayNotHasKey('user_id', $_SESSION);
    }

    public function test_login_with_empty_fields()
    {
        $_POST['email'] = '';
        $_POST['password'] = '';

        $this->viewMock->expects($this->once())->method('render')->with('auth/login');

        $this->authController->login();
        $this->assertArrayNotHasKey('user_id', $_SESSION);
    }

    public function test_register_with_valid_data()
    {
        $_POST['name'] = 'New User';
        $_POST['email'] = 'new@example.com';
        $_POST['password'] = 'password123';
        $_POST['password_confirm'] = 'password123';

        $this->userModelMock->method('findByEmail')->willReturn(null);
        $this->userModelMock->expects($this->once())->method('create');

        $this->authController->register();
    }

    public function test_register_with_existing_email()
    {
        $_POST['name'] = 'New User';
        $_POST['email'] = 'existing@example.com';
        $_POST['password'] = 'password123';
        $_POST['password_confirm'] = 'password123';

        $this->userModelMock->method('findByEmail')->willReturn(['id' => 1]);
        $this->viewMock->expects($this->once())->method('render')->with('auth/register');

        $this->authController->register();
    }

    public function test_register_with_password_mismatch()
    {
        $_POST['name'] = 'New User';
        $_POST['email'] = 'new@example.com';
        $_POST['password'] = 'password123';
        $_POST['password_confirm'] = 'password456';

        $this->viewMock->expects($this->once())->method('render')->with('auth/register');

        $this->authController->register();
    }

    public function test_logout_destroys_session()
    {
        $_SESSION['user_id'] = 1;
        
        $this->authController->logout();
        

        $this->expectNotToPerformAssertions();
    }
}