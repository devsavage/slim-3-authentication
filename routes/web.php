<?php

$app->route(['GET'], '/', App\Http\Controllers\HomeController::class)->setName('home');