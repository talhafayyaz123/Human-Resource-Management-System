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
        Schema::create('product_system', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->nullable();
            $table->unsignedBigInteger('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('system_id')->references('id')->on('systems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_system');
    }
};
