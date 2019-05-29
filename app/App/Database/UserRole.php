<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'users_roles';

    protected $fillable = ['role_id', 'user_id'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}