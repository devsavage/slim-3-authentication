<?php
namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['username', 'email', 'password', 'active', 'active_hash', 'remember_identifier', 'remember_token', 'recover_hash']; 

    public function updateRememberCredentials($identifier, $token)
    {
        $this->update([
            'remember_identifier' => $identifier,
            'remember_token' => $token,
        ]);
    }

    public function removeRememberCredentials()
    {
        $this->updateRememberCredentials(null, null);
    }

    public function userRole()
    {
        return $this->hasOne('\App\Database\UserRole', 'user_id');
    }

    public function hasRole($role)
    {
        if(!$this->userRole) {
            return false;
        }

        return (bool)$this->userRole->{$role};
    }

    public function isAdmin()
    {
        return $this->hasRole('is_admin');
    }
}