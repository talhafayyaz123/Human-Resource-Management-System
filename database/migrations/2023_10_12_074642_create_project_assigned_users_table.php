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
        if (!Schema::hasTable('project_assigned_users')) {
            Schema::create('project_assigned_users', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->references('id')->on('projects')->cascadeOnDelete();
                $table->foreignId('user_profile_id')->references('id')->on('user_profile_data')->cascadeOnDelete();
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
        Schema::dropIfExists('project_assigned_users');
    }
};
