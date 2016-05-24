<?php
namespace Savage\Http\Auth\Models;

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Permissions extends Eloquent
{
    protected $table = 'permissions';

    protected $fillable = [
        'is_admin',
    ];

    public static $defaults = [
        'is_admin' => false,
    ];
}
