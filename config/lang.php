<?php
return [
    'lang' => [
        'mail' => [
            'activation' => [
                'subject' => env("APP_NAME") . " Account Activation",
            ]
        ],

        'alerts' => [
            'registration' => [
                'successful' => "Your account has been created!",
                'error' => "Please fix any errors with your registration and try again.",
                'recaptcha_failed' => "Please verify you are not a robot by completing the reCAPTCHA.",
                'requires_mail_activation' => "Your account has been created but you will need to activate it. Please check your e-mail for instructions.",
            ],

            'login' => [
                'invalid' => "You have supplied invalid credentials.",
                'error' => "Please enter your credentials to continue.",
                'resend_activation' => "Another activation e-mail has been sent. Please check your e-mail for instructions.",
            ],

            'account' => [
                'already_activated' => "Your account has already been activated.",
                'activatied' => "Your account has been activated! You can now login.",
                'invalid_active_hash' => "The active hash you are trying to use has already expired or never existed.",
            ]
        ]
    ]
];