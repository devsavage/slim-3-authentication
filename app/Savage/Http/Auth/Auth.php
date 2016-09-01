<?php
namespace Savage\Http\Auth;

use Savage\Http\Auth\Models\User;
use Savage\Utils\Helper;
use Savage\Utils\Session;

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

    public function logout()
    {
        Session::destroy(getenv('APP_AUTH_ID'));
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
