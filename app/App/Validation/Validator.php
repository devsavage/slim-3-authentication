<?php
namespace App\Validation;

use Violin\Violin;
use Interop\Container\ContainerInterface;

class Validator extends Violin
{
    protected $container;
    protected $auth;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->auth = $container->auth;

        $this->addFieldMessages([
            'username' => [
                'min' => 'Your username must be a minimum of {$0} characters.',
                'uniqueUsername' => 'This username has already been taken.',
            ],

            'email' => [
                'uniqueEmail' => 'This e-mail is already in use.',
            ],

            'password' => [
                'min' => 'Your password must be a minimum of {$0} characters.',
            ],

            'new_password' => [
                'min' => 'Your new password must be a minimum of {$0} characters.',
            ],

            'confirm_password' => [
                'matches' => 'Confirm Password must match Password.'
            ],

            'confirm_new_password' => [
                'matches' => 'Confirm New Password must match New Password.'
            ],
        ]);

        $this->addRuleMessages([
            'matchesCurrentPassword' => 'Your current password is incorrect.',
        ]);
    }

    public function validate_uniqueUsername($value, $input, $args)
    {
        return !(bool) $this->auth->where('username', $value)->count();
    }

    public function validate_uniqueEmail($value, $input, $args)
    {
        $user = $this->auth->where('email', $value);

        if($this->auth->check() && $this->auth->user()->email === $value) {
            return true;
        }

        return !(bool) $user->count();
    }

    public function validate_matchesCurrentPassword($value, $input, $args)
    {
        if($this->auth->check() && $this->container->hash->verifyPassword($value, $this->auth->user()->password)) {
            return true;
        }

        return false;
    }
}
