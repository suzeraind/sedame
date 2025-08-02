<?php

namespace App\Core;

use PDO;
class Database
{
    /**
     * @var self|null
     */
    public static ?self $instance = null;

    /**
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Database constructor.
     */
    private function __construct()
    {
        $this->pdo = new PDO('sqlite:' . DB_PATH);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * Get the database instance.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Get the PDO instance.
     *
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}

