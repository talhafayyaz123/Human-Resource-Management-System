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
        Schema::create('personal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('user_profile_data')->cascadeOnDelete();
            $table->foreignUuid('department_id')->references('id')->on('user_departments')->cascadeOnDelete();
            $table->foreignUuid('team_id')->references('id')->on('user_teams')->cascadeOnDelete();
            $table->foreignId('job_id')->references('id')->on('jobs')->cascadeOnDelete();
            $table->foreignUuid('location_id')->references('id')->on('user_locations')->cascadeOnDelete();
            $table->foreignId('contract_type_id')->references('id')->on('contract_types')->cascadeOnDelete();
            $table->dateTime('start_date');
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
        Schema::dropIfExists('personal_requests');
    }
};
