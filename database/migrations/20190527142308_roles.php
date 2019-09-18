<?php

use \Migration\Migration;

class Roles extends Migration
{
    public function up()  {
        $this->schema->create('roles', function(Illuminate\Database\Schema\Blueprint $table){
            // Auto-increment id
            $table->increments('id');
            $table->string('title');
            // Required for Eloquent's created_at and updated_at columns
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('roles');
    }
}
