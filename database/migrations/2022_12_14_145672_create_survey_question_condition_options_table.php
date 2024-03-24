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
        Schema::create('survey_question_condition_options', function (Blueprint $table) {
            $table->id();
            $table->string('condition');
            $table->string('value')->nullable();
            $table->enum('operator', ['if', '&&', '||']);
            $table->unsignedBigInteger('condition_id');

            //foreign key constraints
            $table->unsignedBigInteger('next_question_id')->nullable()->references('id')->on('survey_questions');
            $table->unsignedBigInteger('option_id')->references('id')->on('survey_question_options');
            $table->foreign('condition_id')->references('id')->on('survey_question_conditions')->onDelete('cascade');

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
        Schema::dropIfExists('survey_question_condition_options');
    }
};
