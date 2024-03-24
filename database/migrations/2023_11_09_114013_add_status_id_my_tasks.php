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
        Schema::table('my_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('task_status_id')->nullable();
            $table->foreign('task_status_id')->references('id')->on('task_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('my_tasks', function (Blueprint $table) {
            //
        });
    }
};
