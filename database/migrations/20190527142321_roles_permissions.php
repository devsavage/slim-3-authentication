<?php

use \Migration\Migration;

class RolesPermissions extends Migration
{
    public function up()  {
        $this->schema->create('roles_permissions', function(Illuminate\Database\Schema\Blueprint $table){
            // Auto-increment id
            $table->increments('id');
            $table->unsignedInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->unsignedInteger('permission_id');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            //$table->primary(['role_id','permission_id']);
            // Required for Eloquent's created_at and updated_at columns
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('roles_permissions');
    }
}
