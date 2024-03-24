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
        if (!Schema::hasTable("skill_matrix_team_members")) {
            Schema::create('skill_matrix_team_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('skill_matrix_id')->constrained('skill_matrices')->cascadeOnDelete()->cascadeOnUpdate();
                $table->string('team_member_id')->references('user_id')->on('user_profile_data')->cascadeOnDelete()->cascadeOnUpdate();
                $table->string("user_team_id")->nullable();
                $table->foreign("user_team_id")->references("id")->on("user_teams")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('skill_matrix_team_members');
    }
};
