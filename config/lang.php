<?php
return [
    'lang' => [
        'mail' => [
            'activation' => [
                'subject' => env("APP_NAME", "My App") . " Account Activation",
            ],

            'password' => [
                'forgot' => [
                    'subject' => env("APP_NAME", "My App") . " Password Reset Request",
                ]
            ],
        ],

        'alerts' => [
            'requires_auth' => "You must be signed in to access that page.",
            'recaptcha_failed' => "Please verify you are not a robot by completing the reCAPTCHA.",
            'forgot_password_success' => "If your email is valid, you'll receive an email with instructions on how to reset your password.",
            'forgot_password_failed' => "Please enter the e-mail address associated with your account.",
            'reset_password_invalid' => "Your password reset token was invalid. Please submit another password reset request.",
            'reset_password_failed' => "Your password could not be reset at this time.",
            'reset_password_success' => "You have successfully reset your password. You can now login with your new password.",
            'reset_password_no_email' => "Please verify your e-mail.",
            'registration' => [
                'successful' => "Your account has been created!",
                'error' => "Please fix any errors with your registration and try again.",
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

                'password' => [
                    'updated' => "Your password has been changed.",
                    'failed' => "Your password couldn't be changed at this time.",
                ],

                'profile' => [
                    'updated' => "Your profile has been updated!",
                    'failed' => "Your profile couldn't be updated at this time.",
                ]
            ]
        ]
    ]
];