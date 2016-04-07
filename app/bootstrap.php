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

require 'routes.php';

$container['db']->bootEloquent();
