<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/constants.php';

use App\Controllers\Homecontroller;

$controller = new HomeController();
$controller->index();
