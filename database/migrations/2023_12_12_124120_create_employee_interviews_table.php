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
        Schema::create('employee_interviews', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("employee_name");
            $table->string('creator_id');
            $table->json('personal_skills')->nullable();
            $table->json('company_values')->nullable();
            $table->json('social_competence')->nullable();
            $table->json('professional_technical_abilities')->nullable();
            $table->string('goal_acheivement')->nullable();
            $table->string('overall_summary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_interviews');
    }
};
