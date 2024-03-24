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
        Schema::create('time_tracker_governments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('type');
            $table->text('description');

            $table->time('start_time');
            $table->time('end_time')->nullable();

            $table->string('user_id')->nullable();
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
        Schema::dropIfExists('time_tracker_governments');
    }
};