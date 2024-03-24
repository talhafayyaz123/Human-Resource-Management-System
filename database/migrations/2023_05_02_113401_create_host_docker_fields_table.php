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
        if(Schema::hasTable('host_docker_fields')==false) 
        {
            Schema::create('host_docker_fields', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('system_id')->references('id')->on('systems')->onDelete('set null');
                $table->integer('database_size');
                $table->string('system_language');
                $table->integer('system_size');
                $table->string('url')->nullable();
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
        Schema::dropIfExists('host_docker_fields');
    }
};
