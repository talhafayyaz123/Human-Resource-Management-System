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
        Schema::create('product_stores', function (Blueprint $table) {
            $table->id();
            $table->string('item_number');
            $table->string('product_title');
            $table->string('long_description')->nullable();
            $table->string('short_description')->nullable();
            $table->decimal('sell_price');
            $table->boolean('is_visible_for_partner')->default(false);
            $table->boolean('is_visible_for_customer')->default(false);
            $table->string('author_id');
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
        Schema::dropIfExists('product_stores');
    }
};
