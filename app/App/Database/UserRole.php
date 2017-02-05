<?php
namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'users_roles';

    protected $fillable = ['is_admin'];

    public static $defaults = [
        'is_admin' => false,
    ];
}