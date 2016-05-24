<?php

namespace Savage\Http\Auth;

use Savage\Http\Auth\Models\User;
use Savage\Utils\Helper;
use Savage\Utils\Session;

class Auth extends User
{
    public function check()
    {
        // Set this to the same as: $this->container['settings']['auth']['session_key'], we cannot use our container in this class due to Eloquent.
        return Session::exists('user_id');
    }

    public function user()
    {
        // Set this to the same as: $this->container['settings']['auth']['session_key'], we cannot use our container in this class due to Eloquent.
        return User::find(Session::get('user_id'));
    }

    public function attemptLogin($identifier, $password)
    {
        $user = User::where('email', $identifier)->orWhere('username', $identifier)->first();

        if(!$user) {
            return false;
        }

        if(Helper::verifyPassword($password, $user->password)) {
            // Set this to the same as: $this->container['settings']['auth']['session_key'], we cannot use our container in this class due to Eloquent.
            Session::set('user_id', $user->id);
            return true;
        }

        return false;
    }

    public function logout()
    {
        // Set this to the same as: $this->container['settings']['auth']['session_key'], we cannot use our container in this class due to Eloquent.
        Session::destroy('user_id');
    }

    /**
     * Permissions
     */

     public function permissions()
     {
         return $this->hasOne('\Savage\Http\Auth\Models\Permissions', 'user_id');
     }

     public function hasPermission($permission)
     {
         return (bool)$this->permissions->{$permission};
     }

     public function isAdmin()
     {
         return $this->hasPermission('is_admin');
     }
}
