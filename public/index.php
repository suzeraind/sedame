<?php

use App\Core\Router;
use App\Models\User;

$container = require_once __DIR__ . '/../bootstrap/app.php';

new Router($container);
