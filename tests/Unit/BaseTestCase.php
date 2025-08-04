<?php

namespace Tests\Unit;

use App\Core\Facade;
use App\Core\View;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected $viewMock;
    protected $userModelMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->viewMock = $this->createMock(View::class);
        $this->userModelMock = $this->createMock(User::class);

        $this->viewMock->method('with')->willReturn($this->viewMock);
        $this->viewMock->method('layout')->willReturn($this->viewMock);

        $_SESSION = [];
        $_POST = [];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->viewMock, $this->userModelMock);
        $_SESSION = [];
        $_POST = [];
        Facade::clearSwappedInstances();
    }
}
