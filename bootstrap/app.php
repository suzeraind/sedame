<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../helpers/helper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
