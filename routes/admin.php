<?php

$app->group('/admin', function() {
    $this->route(['GET'], '[/]', App\Http\Controllers\Admin\AdminController::class)->setName('admin.home');

    $this->route(['GET'], '/users[/]', App\Http\Controllers\Admin\AdminUserController::class)->setName('admin.users.list');

    $this->route(['GET'], '/users/{userId}/edit[/]', App\Http\Controllers\Admin\AdminUserController::class, 'edit')->setName('admin.users.edit');

    $this->route(['GET', 'POST'], '/users/{userId}/delete[/]', App\Http\Controllers\Admin\AdminUserController::class, 'delete')->setName('admin.users.delete');

    $this->route(['GET', 'POST'], '/users/{userId}/edit/profile', App\Http\Controllers\Admin\AdminUserController::class, 'editProfile')->setName('admin.users.edit.profile');

    $this->route(['GET', 'POST'], '/users/{userId}/edit/settings', App\Http\Controllers\Admin\AdminUserController::class, 'editSettings')->setName('admin.users.edit.settings');

    $this->route(['POST'], '/users/{userId}/update/role/{role}/{action}', App\Http\Controllers\Admin\AdminUserController::class, 'updateRole')->setName('admin.users.update.role');
})->add(new App\Http\Middleware\AdminMiddleware($app->getContainer()));