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
        Schema::create('product_store_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_store_id')->references('id')->on('product_stores')->cascadeOnDelete();
            $table->string('user_id');
            $table->string('title');
            $table->longText('review')->nullable();
            $table->integer('ratting')->default(0);
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
        Schema::dropIfExists('product_store_reviews');
    }
};
