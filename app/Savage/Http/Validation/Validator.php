<?php

namespace Savage\Http\Validation;

use Savage\Http\Auth\Models\User;

use Savage\Utils\Helper;

use Violin\Violin;

/**
 * Validator allows us to validate a user's input in requests.
 */

class Validator extends Violin
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;

        $this->addFieldMessages([
            'password' => [
                'min' => 'Your password must be a minimum of {$0} characters.',
                'max' => 'Your password cannot exceed {$0} characters.',
            ],

            'confirm_password' => [
                'matches' => 'Confirm Password must match Password.',
            ],

            'confirm_new_password' => [
                'matches' => 'Confirm New Password must match New Password.',
            ]
        ]);

        $this->addRuleMessages([
            'matchesCurrentPassword' => 'Your current password is invalid.',
            'uniqueUsername' => 'That username is already in use.',
            'uniqueEmail' => 'That e-mail is already in use.',
        ]);
    }

    public function validate_uniqueUsername($value, $input, $args)
    {
        return !(bool) User::where('username', $value)->count();
    }

    public function validate_uniqueEmail($value, $input, $args)
    {
        $authed = $this->container->auth->check();
        $user = $authed ? $this->container->auth->user() : false;

        if($user && $user->email === $value) {
            return true;
        }

        return !(bool) User::where('email', $value)->count();
    }

    public function validate_matchesCurrentPassword($value, $input, $args)
    {
        $authed = $this->container->auth->check();
        $user = $authed ? $this->container->auth->user() : false;

        if($user && Helper::verifyPassword($value, $user->password)) {
            return true;
        }

        return false;
    }
}
