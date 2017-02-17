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

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles', 'user_id');
    }

    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'user_id');
    }

    public function giveRole($title)
    {
        $role = Role::where('title', $title)->first();

        if(!$role) {
            return false;
        }

        $userRoles = $this->userRoles();

        if($userRoles->where('role_id', $role->id)->first()) {
            return true;
        }

        return $this->userRoles()->create([
            'role_id' => $role->id,
        ]);
    }

    public function hasRole(...$roles)
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('title', $role)) {
                return true;
            }
        }

        return false;
    }
}