<?php
session_start();

use Savage\Http\Site as App;
use Slim\Container;

define('INC_ROOT', dirname(__DIR__));

require_once INC_ROOT . '/vendor/autoload.php';

$app = new App(new Container(
    include INC_ROOT . '/config/development.php'
));

$container = $app->getContainer();

$app->add(new \Savage\Http\Middleware\CsrfMiddleware($container));

$app->add($container->csrf);

$container['db']->bootEloquent();

require 'routes.php';
