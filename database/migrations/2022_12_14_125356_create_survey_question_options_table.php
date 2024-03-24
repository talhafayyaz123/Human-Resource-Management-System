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
        Schema::create('survey_question_options', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('title');
            $table->string('file_name')->nullable();
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->integer('step')->nullable();
            $table->string('placeholder')->nullable();
            $table->unsignedBigInteger('survey_question_configurator_id');
            $table->unsignedBigInteger('next_question_id')->nullable()->references('id')->on('survey_questions');
            //foreign key relationships
            
            $table->foreign('survey_question_configurator_id')->references('id')->on('survey_question_configurators')->onDelete('cascade');
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
        Schema::dropIfExists('question_options');
    }
};
