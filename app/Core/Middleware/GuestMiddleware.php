<?php

namespace App\Core\Middleware;

class GuestMiddleware
{
    public function handle(): bool
    {

        if (isset($_SESSION['user_id'])) {
            header('Location: /home');
            exit;
        }

        return true;
    }
}
