<?php
session_start();

use Savage\Http\Site as App;
use Slim\Container;

define('INC_ROOT', dirname(__DIR__));

require_once INC_ROOT . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

$app = new App(new Container(
    include INC_ROOT . '/config/' . getenv('APP_ENV') . '.php'
));

$container = $app->getContainer();

$app->add(new \Savage\Http\Middleware\CsrfMiddleware($container));

$app->add($container->csrf);

$container['db']->bootEloquent();

require 'routes.php';
