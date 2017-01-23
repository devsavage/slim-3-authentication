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

            'confirm_password' => [
                'matches' => 'Confirm Password must match Password.'
            ],
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
}
