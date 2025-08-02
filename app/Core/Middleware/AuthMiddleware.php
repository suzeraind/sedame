<?php

namespace App\Core\Middleware;

class AuthMiddleware
{
    public function handle(): bool
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        return true;
    }
}
