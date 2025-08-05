<?php

namespace Tests\Unit\Controllers;

use App\Controllers\AuthController;
use App\Core\Request;
use App\Core\Response;
use App\Core\View as CoreView;
use App\Facades\View;
use Tests\Unit\BaseTestCase;

class AuthControllerTest extends BaseTestCase
{
    private AuthController $authController;

    protected function setUp(): void
    {
        parent::setUp();

        // Swap the View facade with our mock
        View::swap(CoreView::class, $this->viewMock);

        $this->authController = new AuthController($this->userModelMock);
    }

    private function createRequest(array $data): Request
    {
        return new Request([], $data, [], [], ['REQUEST_METHOD' => 'POST']);
    }

    public function test_login_with_valid_credentials()
    {
        $request = $this->createRequest([
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $user = ['id' => 1, 'name' => 'Test User', 'password' => password_hash('password', PASSWORD_DEFAULT)];
        $this->userModelMock->method('findByEmail')->willReturn($user);

        $response = $this->authController->login($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(302, $response->getStatus());
        $this->assertEquals(1, $_SESSION['user_id']);
        $this->assertEquals('Test User', $_SESSION['username']);
    }

    public function test_login_with_invalid_credentials()
    {
        $request = $this->createRequest([
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $this->userModelMock->method('findByEmail')->willReturn(null);
        $this->viewMock->expects($this->once())->method('render')->with('auth/login')->willReturn('Rendered View');

        $response = $this->authController->login($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(422, $response->getStatus());
        $response->send();
        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->expectOutputString('Rendered View');
    }

    public function test_login_with_empty_fields()
    {
        $request = $this->createRequest(['email' => '', 'password' => '']);

        $this->viewMock->expects($this->once())->method('render')->with('auth/login')->willReturn('Rendered View');

        $response = $this->authController->login($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(422, $response->getStatus());
        $response->send();
        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->expectOutputString('Rendered View');
    }

    public function test_register_with_valid_data()
    {
        $request = $this->createRequest([
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirm' => 'password123'
        ]);

        $this->userModelMock->method('findByEmail')->willReturn(null);
        $this->userModelMock->expects($this->once())->method('create');

        $response = $this->authController->register($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(302, $response->getStatus());
    }

    public function test_register_with_existing_email()
    {
        $request = $this->createRequest([
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirm' => 'password123'
        ]);

        $this->userModelMock->method('findByEmail')->willReturn(['id' => 1]);
        $this->viewMock->expects($this->once())->method('render')->with('auth/register')->willReturn('Rendered View');

        $response = $this->authController->register($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(422, $response->getStatus());
        $response->send();
        $this->expectOutputString('Rendered View');
    }

    public function test_register_with_password_mismatch()
    {
        $request = $this->createRequest([
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirm' => 'password456'
        ]);

        $this->viewMock->expects($this->once())->method('render')->with('auth/register')->willReturn('Rendered View');

        $response = $this->authController->register($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(422, $response->getStatus());
        $response->send();
        $this->expectOutputString('Rendered View');
    }

    public function test_logout_destroys_session()
    {
        $_SESSION['user_id'] = 1;
        
        $response = $this->authController->logout();
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(302, $response->getStatus());
        $this->assertEmpty($_SESSION);
    }
}
