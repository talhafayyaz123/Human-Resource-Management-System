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
        Schema::create('survey_question_conditional_products', function (Blueprint $table) {
            $table->id();

            $table->string('quantity');
            //foreign key relationships
            $table->unsignedBigInteger('condition_id');
            $table->unsignedBigInteger('product_id')->references('id')->on('products');
            $table->foreign('condition_id')->nullable()->references('id')->on('survey_question_conditions')->onDelete('cascade');

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
        Schema::dropIfExists('question_conditional_products');
    }
};
