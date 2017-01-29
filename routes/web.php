<?php

$app->route(['GET'], '/', App\Http\Controllers\HomeController::class)->setName('home');

$app->group('/auth', function() {
    $this->route(['GET', 'POST'], '/login', App\Http\Controllers\Auth\LoginController::class)->setName('auth.login');
    $this->route(['GET', 'POST'], '/register', App\Http\Controllers\Auth\RegisterController::class)->setName('auth.register');
});

$app->route(['GET'], '/auth/activate', App\Http\Controllers\Auth\ActivationController::class)->setName('auth.activate');
$app->route(['GET'], '/auth/activate/resend', App\Http\Controllers\Auth\ActivationController::class, 'resend')->setName('auth.activate.resend');

$app->get('/auth/logout', function($request, $response, $args) {
    App\Lib\Session::destroy(getenv('APP_AUTH_ID'));
    return $response->withRedirect($this['router']->pathFor('home'));
})->setName('auth.logout');