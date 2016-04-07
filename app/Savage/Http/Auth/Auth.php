<?php

namespace Savage\Http\Auth;

use Interop\Container\ContainerInterface;

use Savage\Http\Auth\Models\User;
use Savage\Utils\Helper;
use Savage\Utils\Session;

class Auth extends User
{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function check()
    {
        return Session::exists($this->container['settings']['auth']['session_key']);
    }

    public function user()
    {
        return User::find($this->container['settings']['auth']['session_key']);
    }

    public function login($identifier, $password)
    {
        $user = User::where('email', $identifier)->orWhere('username', $identifier)->first();

        if(!$user) {
            return false;
        }

        if(Helper::verifyPassword($password, $user->password)) {
            Session::set($this->container['settings']['auth']['session_key'], $user->id);
            return true;
        }

        return false;
    }

    public function logout()
    {
        Session::destroy($this->container['settings']['auth']['session_key']);
    }
}
