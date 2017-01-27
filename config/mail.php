<?php
return [
    'mail' => [
        'host' => env('MAIL_HOST'),
        'port' => env('MAIL_PORT'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'from.name' => env('MAIL_FROM_NAME', 'noreply'),
        'from' => env('MAIL_FROM', 'noreply@example.com'),
    ]
];