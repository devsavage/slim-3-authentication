<?php

$app->route(['GET'], '/', App\Http\Controllers\HomeController::class)->setName('home');

$app->group('/auth', function() {
    $this->route(['GET', 'POST'], '/login', App\Http\Controllers\Auth\LoginController::class)->add(new App\Http\Middleware\GuestMiddleware($this->getContainer()))->setName('auth.login');
    
    $this->route(['GET', 'POST'], '/register', App\Http\Controllers\Auth\RegisterController::class)->add(new App\Http\Middleware\GuestMiddleware($this->getContainer()))->setName('auth.register');

    $this->route(['GET'], '/settings', App\Http\Controllers\Auth\SettingsController::class)->add(new App\Http\Middleware\AuthMiddleware($this->getContainer()))->setName('auth.settings');
    
    $this->route(['GET', 'POST'], '/settings/profile', App\Http\Controllers\Auth\SettingsController::class, 'profile')->add(new App\Http\Middleware\AuthMiddleware($this->getContainer()))->setName('auth.settings.profile');
    
    $this->route(['GET', 'POST'], '/settings/password', App\Http\Controllers\Auth\SettingsController::class, 'password')->add(new App\Http\Middleware\AuthMiddleware($this->getContainer()))->setName('auth.settings.password');

    $this->route(['GET'], '/activate', App\Http\Controllers\Auth\ActivationController::class)->setName('auth.activate');
    
    $this->route(['GET'], '/activate/resend', App\Http\Controllers\Auth\ActivationController::class, 'resend')->setName('auth.activate.resend');

    $this->route(['GET', 'POST'], '/password/forgot', App\Http\Controllers\Auth\PasswordResetController::class, 'forgot')->add(new App\Http\Middleware\GuestMiddleware($this->getContainer()))->setName('auth.password.forgot');

    $this->route(['GET', 'POST'], '/password/reset', App\Http\Controllers\Auth\PasswordResetController::class, 'reset')->add(new App\Http\Middleware\GuestMiddleware($this->getContainer()))->setName('auth.password.reset');
});

$app->get('/auth/logout', function($request, $response, $args) {
    if(App\Lib\Cookie::exists(env('APP_REMEMBER_ID', 'APP_REMEMBER_TOKEN'))) {
        $this['auth']->user()->removeRememberCredentials();
        App\Lib\Cookie::destroy(env('APP_REMEMBER_ID', 'APP_REMEMBER_TOKEN'));
    }

    App\Lib\Session::destroy(env('APP_AUTH_ID', 'user_id'));

    return $response->withRedirect($this['router']->pathFor('home'));
})->add(new App\Http\Middleware\AuthMiddleware($app->getContainer()))->setName('auth.logout');