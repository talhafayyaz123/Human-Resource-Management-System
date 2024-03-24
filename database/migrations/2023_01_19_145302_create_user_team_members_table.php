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
        Schema::create('user_team_members', function (Blueprint $table) {
            $table->id();
            $table->string('team_member_id')->references('user_id')->on('user_profile_data')->onDelete('cascade'); //Team member
            $table->string('user_team_id')->references('id')->on('user_teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_team_members');
    }
};
