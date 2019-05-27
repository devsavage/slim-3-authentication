<?php

use \Migration\Migration;

class Permissions extends Migration
{
    public function up()  {
        $this->schema->create('permissions', function(Illuminate\Database\Schema\Blueprint $table){
            // Auto-increment id
            $table->increments('id');
            $table->string('name');
            // Required for Eloquent's created_at and updated_at columns
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('permissions');
    }
}
