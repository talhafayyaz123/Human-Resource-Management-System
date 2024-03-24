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
        Schema::create('partner_order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_order_id')->references('id')->on('partner_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->string('type')->comment('partner_price_list/own_price_list');
            $table->decimal('quantity')->default(1.00);
            $table->decimal('hourly_rate')->nullable();
            $table->decimal('sale_price')->nullable();
            $table->decimal('maintenance_rate')->nullable();
            $table->decimal('tax')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('total_netto')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->foreignId('payment_period')->nullable()->references('id')->on('payment_periods')->cascadeOnDelete();
            $table->decimal('price_per_period')->nullable();
            $table->decimal('duration')->nullable();
            $table->longText('additional_description')->nullable();
            $table->string('product_name')->nullable();
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
        Schema::dropIfExists('partner_order_products');
    }
};
