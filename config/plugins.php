<?php
return [
    'plugins' => [
        'recaptcha' => [
            'public' => env('RECAPTCHA_PUBLIC'),
            'secret' => env('RECAPTCHA_SECRET'),
        ]
    ]
];