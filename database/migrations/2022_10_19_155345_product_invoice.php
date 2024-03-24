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
        Schema::create('product_invoice', function (Blueprint $table) {
            $table->id();
            $table->string('sale_price');
            $table->integer('discount')->nullable();
            $table->string('price_without_tax');
            $table->string('total_price');
            $table->integer('quantity');
            $table->integer('hourly_rate')->nullable();
            $table->integer('service_hours')->nullable();
            $table->decimal('maintenance_rate')->nullable();
            $table->decimal('tax')->nullable();

            //Add foreign key relationship
            $table->unsignedBigInteger('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('payment_period')->references('id')->on('payment_periods')->onDelete('cascade')->nullable();

            //Add foreign key relationship
            $table->unsignedBigInteger('invoice_id')->references('id')->on('invoices')->onDelete('cascade');

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
