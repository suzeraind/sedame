<?php

use App\Core\Router;
use App\Models\User;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../helpers/helper.php';

new Router;
$users = new User()->all();
pp($users);