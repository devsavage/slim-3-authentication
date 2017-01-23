<?php
namespace App\Auth;

use App\Database\User;
use App\Lib\Session;

class Auth extends User
{
    public function check()
    {
        return Session::exists(getenv('APP_AUTH_ID'));
    }

    public function user()
    {
        return User::find(Session::get(getenv('APP_AUTH_ID')));
    }
}