<?php

use App\Core\Request;
use App\Core\Router;

$container = require_once __DIR__ . '/../bootstrap/app.php';

$request = Request::createFromGlobals();

$container->bind(Request::class, fn() => $request);

$router = new Router($container);

$response = $router->dispatch($request);

$response->send();
