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
        Schema::create('company_price_list', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('customer_id');
            $table->unsignedBigInteger('price_list_id');

            $table->foreign('customer_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('price_list_id')->references('id')->on('price_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_price_list');
    }
};
