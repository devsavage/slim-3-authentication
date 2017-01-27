<?php
namespace App\Auth;

use App\Database\User;
use App\Lib\Session;

class Auth extends User
{
    public function check()
    {
        return Session::exists(env('APP_AUTH_ID', 'user_id'));
    }

    public function user()
    {
        return User::find(Session::get(env('APP_AUTH_ID', 'user_id')));
    }
}