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
        Schema::create('user_teams', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->string('name');

            $table->string('team_lead_id')->references('user_id')->on('user_profile_data')->onDelete('set null')->nullable(); // team lead
            $table->string('department_id')->references('id')->on('departments')->onDelete('set null')->nullable(); //Department

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
        Schema::dropIfExists('user_teams');
    }
};
