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
        Schema::create('product_store_review_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->references('id')->on('product_store_reviews')->cascadeOnDelete();
            $table->string('user_id');
            $table->boolean('is_helpful')->nullable();
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
        Schema::dropIfExists('product_store_review_feedbacks');
    }
};
