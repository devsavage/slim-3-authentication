<?php
namespace App\Database;

use App\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasPermissionsTrait;

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

    public function revokeRecoveryHash()
    {
        $this->update([
            'recover_hash' => null,
        ]);
    }

    public function activate($value = true, $hash = null)
    {
        $this->update([
            'active' => $value,
            'active_hash' => $hash
        ]);
    }

    public function deactivate($hash = null)
    {
        $this->activate(false, $hash);
    }

    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Make this functionality better.
     */

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

    /**
     * Make this functionality better.
     */

    public function removeRole($title)
    {
        $role = Role::where('title', $title)->first();

        if(!$role) {
            return false;
        }

        $userRole = $this->userRoles()->where('role_id', $role->id)->first();

        if($userRole) {
            return $userRole->delete();
        }

        return true;
    }
    
    public function can($action)
    {
        $permission = Permission::where('name', $action)->first();

        if(!$permission) {
            return false;
        }

        return $this->hasPermissionTo($permission);
    }

    public function canEdit(User $user)
    {
        if($this->isSuperAdmin() && $user->isSuperAdmin()) {
            return false;
        }
        
        if($user->isAdmin() && $this->can('edit admins') || !$user->isAdmin() && $this->can('edit users') && !$user->isSuperAdmin()) {
            return true;
        }

        return false;
    }
}