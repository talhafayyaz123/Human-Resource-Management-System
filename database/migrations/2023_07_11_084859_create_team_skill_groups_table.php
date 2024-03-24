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
        Schema::create('team_skill_groups', function (Blueprint $table) {
            $table->id();
            $table->string("user_teams_id")->nullable();
            $table->foreign("user_teams_id")->references("id")->on("user_teams")->cascadeOnDelete()->cascadeOnUpdate()->nullable();
            $table->foreignId('skill_group_id')->constrained('skill_groups')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('team_skill_groups');
    }
};
