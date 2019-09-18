<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination;
use Illuminate\Pagination\Paginator;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = ['title', 'hidden'];
    
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'roles_permissions');
    }

    public function givePermissionTo(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }
}
