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
        Schema::table('employee_interviews', function (Blueprint $table) {
            //employee tab values
            $table->string('interview_reason')->nullable();

            //team tab values
            $table->json('team_personal_skills')->nullable();
            $table->json('team_company_values')->nullable();
            $table->json('team_social_competence')->nullable();
            $table->json('team_professional_technical_abilities')->nullable();
            $table->string('team_goal_achievement')->nullable();
            $table->string('team_overall_summary')->nullable();


            //final tab values
            $table->json('final_personal_skills')->nullable();
            $table->json('final_company_values')->nullable();
            $table->json('final_social_competence')->nullable();
            $table->json('final_professional_technical_abilities')->nullable();
            $table->string('final_goal_achievement')->nullable();
            $table->string('final_overall_summary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_interviews', function (Blueprint $table) {
            //
        });
    }
};
