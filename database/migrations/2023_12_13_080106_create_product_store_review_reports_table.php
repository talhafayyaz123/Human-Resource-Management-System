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
        Schema::create('product_store_review_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->references('id')->on('product_store_reviews')->cascadeOnDelete();
            $table->foreignId('review_report_id')->references('id')->on('review_reports')->cascadeOnDelete();
            $table->string('user_id');
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
        Schema::dropIfExists('product_store_review_reports');
    }
};
