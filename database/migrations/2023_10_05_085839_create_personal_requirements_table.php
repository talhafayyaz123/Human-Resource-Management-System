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
        Schema::create('personal_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_request_id')->references('id')->on('personal_requests')->cascadeOnDelete();
            $table->foreignId('approver_id')->references('id')->on('user_profile_data')->cascadeOnDelete();
            $table->string('status');
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
        Schema::dropIfExists('personal_requirements');
    }
};
