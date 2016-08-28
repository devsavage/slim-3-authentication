<?php

$app->route(['GET'], '/', \Savage\Http\Controllers\HomeController::class)->setName('home');
$app->route(['GET'], '/test', \Savage\Http\Controllers\HomeController::class, 'test')->setName('test');

$app->group('/auth', function() {
    $this->route(['GET', 'POST'], '/register', \Savage\Http\Controllers\AuthController::class, 'register')->setName('auth.register');
    $this->route(['GET', 'POST'], '/login', \Savage\Http\Controllers\AuthController::class, 'login')->setName('auth.login');
    $this->route(['GET'], '/activate', \Savage\Http\Controllers\ActivateController::class)->setName('auth.activate');
})->add(new \Savage\Http\Middleware\GuestMiddleware($container));

$app->group('/auth', function() {
    $this->route(['GET'], '/account', \Savage\Http\Controllers\AuthController::class, 'account')->setName('auth.account');
    $this->route(['POST'], '/profile', \Savage\Http\Controllers\AuthController::class, 'profile')->setName('auth.profile');
    $this->route(['POST'], '/password/change', \Savage\Http\Controllers\AuthController::class, 'changePassword')->setName('auth.password.change');
    $this->route(['GET'], '/logout', \Savage\Http\Controllers\AuthController::class, 'logout')->setName('auth.logout');
})->add(new \Savage\Http\Middleware\AuthMiddleware($container));
