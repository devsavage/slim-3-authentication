<?php

use \Migration\Migration;

class UsersRoles extends Migration
{
    public function up()  {
        $this->schema->create('users_roles', function(Illuminate\Database\Schema\Blueprint $table){
            // Auto-increment id
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            // Required for Eloquent's created_at and updated_at columns
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('users_roles');
    }
}
