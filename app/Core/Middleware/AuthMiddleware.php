<?php

namespace App\Core\Middleware;

use App\Core\Contracts\IMiddleware;

class AuthMiddleware implements IMiddleware
{
    public function handle(): bool
    {

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        return true;
    }
}
