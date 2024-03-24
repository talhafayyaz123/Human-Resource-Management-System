<?php

use App\Enums\QuestionType;
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
        Schema::create('survey_question_configurators', function (Blueprint $table) {
            $table->id();
            $table->enum('type', QuestionType::ALL)->nullable();
            $table->unsignedBigInteger('survey_question_id');
            //foreign key relationships
            $table->foreign('survey_question_id')->references('id')->on('survey_questions')->onDelete('cascade');

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
        Schema::dropIfExists('question_configurators');
    }
};
