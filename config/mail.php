<?php
return [
    'mail' => [
        'host' => getenv('MAIL_HOST'),
        'port' => getenv('MAIL_PORT'),
        'username' => getenv('MAIL_USERNAME'),
        'password' => getenv('MAIL_PASSWORD'),
        'from.name' => getenv('MAIL_FROM_NAME'),
        'from' => getenv('MAIL_FROM'),
    ]
];