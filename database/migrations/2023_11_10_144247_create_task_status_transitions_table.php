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
        Schema::create('task_status_transitions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('from_status_id');
            $table->unsignedBigInteger('to_status_id');
            // Foreign key constraints
            $table->foreign('from_status_id')->references('id')->on('task_statuses')->onDelete('cascade');
            $table->foreign('to_status_id')->references('id')->on('task_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_status_transitions');
    }
};
