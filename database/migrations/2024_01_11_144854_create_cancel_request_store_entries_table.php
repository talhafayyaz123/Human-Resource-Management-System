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
        Schema::create('cancel_request_store_entry', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("cancelation_request_id");
            $table->foreign('cancelation_request_id')->references('id')->on('cancelation_requests')->onDelete('cascade');

            $table->foreignId('store_entry_id')->references('id')->on('product_stores')->cascadeOnDelete();


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
        Schema::dropIfExists('cancel_request_store_entry');
    }
};
