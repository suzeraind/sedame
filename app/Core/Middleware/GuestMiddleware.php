<?php

namespace App\Core\Middleware;

use App\Core\Contracts\IMiddleware;

class GuestMiddleware implements IMiddleware
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
