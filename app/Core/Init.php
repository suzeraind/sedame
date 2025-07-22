<?php

namespace App\Core;

use App\Controllers\Homecontroller;


class Init
{
    public static function set(): void
    {
        $controller = new Homecontroller;
        $controller->index();
    }
}
