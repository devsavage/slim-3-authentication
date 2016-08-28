<?php
namespace Savage\Http\Auth\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'email',
        'remember_token',
        'remember_identifier',
        'active',
        'active_hash',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'remember_identifier',
        'active_hash',
    ];
}
