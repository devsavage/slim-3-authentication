<?php
namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['username', 'email', 'password', 'active', 'active_hash', 'remember_token', 'remember_token'];   
}