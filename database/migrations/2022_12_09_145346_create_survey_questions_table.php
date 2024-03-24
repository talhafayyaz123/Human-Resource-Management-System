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
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('text')->nullable();
            $table->text('description')->nullable();
            $table->longText('product_details')->nullable();
            $table->string('survey_id');
            $table->bigInteger('question_order')->nullable();

            //foreign key constraints
            $table->unsignedBigInteger('survey_chapter_id')->nullable();
            $table->unsignedBigInteger('next_question_id')->nullable()->references('id')->on('survey_questions');
            $table->foreign('survey_chapter_id')->nullable()->references('id')->on('survey_chapters')->onDelete('cascade');
            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
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
        Schema::dropIfExists('survey_questions');
    }
};