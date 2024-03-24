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
        Schema::create('cloud_infrastructures', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->string("name")->nullable();
            $table->string("system_number")->nullable();
            $table->enum('type', ['premise', 'cloud', 'hosting']);
            $table->enum('instance_type', ['development', 'test', 'productive', 'database']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cloud_infrastructures');
    }
};