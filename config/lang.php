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
                'not_activated' => "The account you are trying to access has not been activated. <a class='alert-link' href='#'>Resend activation link</a>",
                'error' => "Please enter your credentials to continue."
            ],

            'account' => [
                'already_activated' => "Your account has already been activated.",
                'activatied' => "Your account has been activated! You can now login.",
            ]
        ]
    ]
];