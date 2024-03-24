<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        if (!Schema::hasTable('user_job_leaves')) {
            Schema::create('user_job_leaves', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_job_id');
                $table->integer('total_annual_leaves');
                $table->integer('additional_leaves');
                $table->integer('leave_year');
                $table->foreign('user_job_id')->references('id')->on('user_job_data');
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
        Schema::dropIfExists('user_job_leaves');
    }
};
