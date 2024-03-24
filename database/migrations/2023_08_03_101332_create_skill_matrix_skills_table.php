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
        Schema::create('skill_matrix_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_matrix_id')->constrained('skill_matrices')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('skill_matrix_skills');
    }
};
