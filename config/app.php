<?php
return [
    'app' => [
        'environment' => env('APP_ENV', 'development'),
        'url' => env('APP_URL', 'http://127.0.0.1'),
        'activation' => [
            'method' => env('APP_ACTIVATION_METHOD', 'mail'),
        ],
        'auth_id' => env('APP_AUTH_ID', 'user_id'),
        'remember_id' => env('APP_REMEMBER_ID', 'APP_REMEMBER_TOKEN'),
    ]
];