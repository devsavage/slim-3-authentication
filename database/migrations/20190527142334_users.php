<?php

use \Migration\Migration;

class Users extends Migration
{
    public function up()  {
        $this->schema->create('users', function(Illuminate\Database\Schema\Blueprint $table){
            // Auto-increment id
            $table->increments('id');
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->boolean('active')->default(0);
            $table->string('active_hash')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('remember_identifier')->nullable();
            $table->string('recover_hash')->nullable();
            // Required for Eloquent's created_at and updated_at columns
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('users');
    }
}
