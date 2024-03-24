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
        Schema::create('module_histories', function (Blueprint $table) {
            $table->id();
            $table->string('moduleable_type');
            $table->string('moduleable_id');
            $table->string('user_id');
            $table->string('module_action')->comment('create, update');
            $table->string('request_ip')->nullable();
            $table->longText('old_fields_data')->nullable();
            $table->longText('new_fields_data')->nullable();
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
        Schema::dropIfExists('module_histories');
    }
};
