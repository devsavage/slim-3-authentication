<?php

use \Migration\Migration;

class UsersPermissions extends Migration
{
    public function up()  {
        $this->schema->create('users_permissions', function(Illuminate\Database\Schema\Blueprint $table){
            // Auto-increment id
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('permission_id');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            // Required for Eloquent's created_at and updated_at columns
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('users_permissions');
    }
}
