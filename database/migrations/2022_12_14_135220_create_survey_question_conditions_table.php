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
        Schema::create('survey_question_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('discount')->nullable();
            $table->unsignedBigInteger('survey_question_configurator_id');
            //foreign key relationships
            $table->unsignedBigInteger('next_question_id')->nullable()->references('id')->on('survey_questions');
            $table->foreign('survey_question_configurator_id', 'configurator_condition_foreign')->references('id')->on('survey_question_configurators')->onDelete('cascade');
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
        Schema::dropIfExists('question_conditions');
    }
};
