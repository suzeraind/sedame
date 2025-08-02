<?php

namespace Tests\Unit\Core;

use App\Core\Database;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function test_get_instance_returns_database_object(): void
    {
        $instance = Database::getInstance();
        $this->assertInstanceOf(Database::class, $instance);
    }

    public function test_get_instance_returns_same_instance(): void
    {
        $instance1 = Database::getInstance();
        $instance2 = Database::getInstance();
        $this->assertSame($instance1, $instance2);
    }

    public function test_get_pdo_returns_pdo_object(): void
    {
        $pdo = Database::getInstance()->getPdo();
        $this->assertInstanceOf(PDO::class, $pdo);
    }
}
