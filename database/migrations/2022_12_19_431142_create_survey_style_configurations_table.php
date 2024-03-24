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
        Schema::create('survey_style_configurations', function (Blueprint $table) {
            $table->id();
            $table->json('steps')->nullable();
            $table->json('question_details')->nullable();
            $table->json('cart')->nullable();
            $table->json('product_details')->nullable();
            $table->json('layout')->nullable();

            //foreign key constraints
            $table->string('survey_id');
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
        Schema::dropIfExists('survey_style_configurations');
    }
};
