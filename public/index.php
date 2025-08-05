<?php

/**
 * Sedame - A simple PHP MVC project
 *
 * @package  Sedame
 * @author   Nikita Sazhnev <nikitas4478@gmail.com>
 */

/**
 * Create The Application
 *
 * The first thing we will do is create a new application instance
 * which serves as the "glue" for all the components of Sedame.
 */
$app = require_once __DIR__ . '/../bootstrap/app.php';

/**
 * Run The Application
 *
 * Once we have the application, we can handle the incoming request
 * and send the generated response back to the client's browser.
 */
$app->run();
