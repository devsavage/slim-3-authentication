<?php
return [
    'database' => [
        'driver' => getenv('DB_CONNECTION'),
        'host' => getenv('DB_HOST'),
        'port' => getenv('DB_PORT'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'database' =>  getenv('DB_DATABASE'),
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ]
];