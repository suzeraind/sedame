<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    private static ?array $user = null;

    /**
     * Check if user is authenticated
     */
    public static function check(): bool
    {
        session_start();
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current user
     */
    public static function user(): ?array
    {
        if (self::$user !== null) {
            return self::$user;
        }

        session_start();

        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $userModel = new User();
        self::$user = $userModel->find($_SESSION['user_id']);

        return self::$user;
    }

    /**
     * Get user ID
     */
    public static function id(): ?int
    {
        session_start();
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get user name
     */
    public static function name(): ?string
    {
        $user = self::user();
        return $user['name'] ?? $user['username'] ?? null;
    }
}
