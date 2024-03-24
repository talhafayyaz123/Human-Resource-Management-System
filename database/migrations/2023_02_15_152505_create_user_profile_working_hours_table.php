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
        Schema::create('user_working_hours', function (Blueprint $table) {
            $table->id();
            $table->string('day');
            $table->integer('working_hours')->nullable();

            $table->unsignedBigInteger('user_job_id');
            $table->foreign('user_job_id')->references('id')->on('user_job_data')->onDelete('cascade');
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
        Schema::dropIfExists('user_profile_working_hours');
    }
};
