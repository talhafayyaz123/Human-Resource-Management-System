<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\QuestionType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_question_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', QuestionType::ALL)->nullable();
            $table->unsignedBigInteger('survey_question_configurator_id');
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
        Schema::dropIfExists('survey_question_groups');
    }
};
