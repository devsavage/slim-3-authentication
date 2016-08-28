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
        if(!$this->permissions) {
            return false;
        }

        return (bool)$this->permissions->{$permission};
     }

     public function isAdmin()
     {
         return $this->hasPermission('is_admin');
     }
}
