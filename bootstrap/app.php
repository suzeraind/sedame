<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../helpers/helper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Core\Container;
use App\Core\Database;
use App\Core\View;
use App\Models\User;

$container = new Container();

$container->bind(Database::class, function () {
    return Database::getInstance();
});

$container->bind(View::class, function () {
    return new View();
});

return $container;
