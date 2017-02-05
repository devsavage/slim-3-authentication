<?php
return [
    'database' => [
        'driver' => env('DB_CONNECTION', 'mysql'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD'),
        'database' =>  env('DB_DATABASE', 'auth'),
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ]
];