<?php

namespace App\Core;

use PDO;
class Database
{
    public static ?self $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $this->pdo = new PDO('sqlite:' . DB_PATH);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public static function getInstance()
    {
        return self::$instance ??= new self();
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}
