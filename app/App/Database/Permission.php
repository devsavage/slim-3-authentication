<?php
namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_permissions');
    }
}