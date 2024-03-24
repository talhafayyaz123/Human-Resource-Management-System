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
        Schema::create('survey_question_option_products', function (Blueprint $table) {
            $table->id();

            $table->string('quantity');
            //foreign key relationships
            $table->unsignedBigInteger('option_id');
            $table->unsignedBigInteger('product_id')->references('id')->on('products');
            option_id')->references('id')->on('survey_question_options')->onDelete('cascade');

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
        //
    }
};
