<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('distributed_filesystems')==false) 
        {
            Schema::create('distributed_filesystems', function (Blueprint $table) {
                $table->id();
                $table->string("name");
                $table->enum("sub_type", ["public", "private"]);
                $table->enum("instance_type", ["development", "test", "productive"]);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distributed_filesystems');
    }
};
