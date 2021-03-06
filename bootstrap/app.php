<?php
session_start();

use Dotenv\Dotenv;
use Noodlehaus\Config;
use App\App;
use App\Http\Middleware\CsrfMiddleware;
use App\Http\Middleware\OldInputMiddleware;
use App\Http\Middleware\RememberMiddleware;
use App\Http\Middleware\Pagination;
use Slim\Container;

define('INC_ROOT', __DIR__);

require INC_ROOT . '/../vendor/autoload.php';

if(file_exists(__DIR__ . '/../.env')) {
    $env = Dotenv::create(__DIR__ . '/../');
    $env->load();
}

$app = new App(new Container(
    include INC_ROOT . '/container.php'
));

$container = $app->getContainer();

$container['config'] = function($c) {
    return new Config(INC_ROOT . '/../config');
};

$app->add(RememberMiddleware::class);
$app->add(CsrfMiddleware::class);
$app->add(OldInputMiddleware::class);
$app->add(Pagination::class) ;

$app->add($container->csrf);

$container['db']->bootEloquent();

require INC_ROOT . '/../routes/web.php';
require INC_ROOT . '/../routes/admin.php';
