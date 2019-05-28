<?php

$app->group('/admin', function() {
    $this->route(['GET'], '[/]', App\Http\Controllers\Admin\AdminController::class)->setName('admin.home');

    //USERS
    $this->route(['GET'], '/users[/]', App\Http\Controllers\Admin\AdminUserController::class)->setName('admin.users.list');
    $this->route(['GET'], '/users/{userId}/edit[/]', App\Http\Controllers\Admin\AdminUserController::class, 'edit')->setName('admin.users.edit');
    $this->route(['GET', 'POST'], '/users/{userId}/delete[/]', App\Http\Controllers\Admin\AdminUserController::class, 'delete')->setName('admin.users.delete');
    $this->route(['GET', 'POST'], '/users/{userId}/edit/profile', App\Http\Controllers\Admin\AdminUserController::class, 'editProfile')->setName('admin.users.edit.profile');
    $this->route(['GET', 'POST'], '/users/{userId}/edit/settings', App\Http\Controllers\Admin\AdminUserController::class, 'editSettings')->setName('admin.users.edit.settings');
    $this->route(['POST'], '/users/{userId}/update/role/{role}/{action}', App\Http\Controllers\Admin\AdminUserController::class, 'updateRole')->setName('admin.users.update.role');

    //ROLES
    $this->route(['GET'], '/roles[/]', App\Http\Controllers\Admin\AdminRoleController::class)->setName('admin.roles.list');
    $this->route(['GET','POST'], '/roles/{roleId}/edit[/]', App\Http\Controllers\Admin\AdminRoleController::class, 'edit')->setName('admin.roles.edit');
    $this->route(['GET','POST'], '/roles/create[/]', App\Http\Controllers\Admin\AdminRoleController::class, 'create')->setName('admin.roles.create');
    $this->route(['GET', 'POST'], '/roles/{roleId}/delete[/]', App\Http\Controllers\Admin\AdminRoleController::class, 'delete')->setName('admin.roles.delete');

})->add(new App\Http\Middleware\AdminMiddleware($app->getContainer()));