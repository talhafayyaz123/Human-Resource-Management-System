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
        Schema::create('invoice_template_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_template_id')->references('id')->on('invoice_templates')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->nullOnDelete();
            $table->decimal('quantity')->default(1);
            $table->decimal('sale_price')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('price_without_tax')->nullable();
            $table->decimal('total_price')->nullable();
            $table->decimal('hourly_rate')->nullable();
            $table->decimal('service_hours')->nullable();
            $table->decimal('maintenance_rate')->nullable();
            $table->decimal('tax')->nullable();
            $table->integer('payment_period')->nullable();
            $table->decimal('price_per_period')->nullable();
            $table->integer('license_id')->nullable();
            $table->decimal('duration')->nullable();
            $table->longText('additional_description')->nullable();
            $table->integer('parent_invoice_template_product_id')->nullable();
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
        Schema::dropIfExists('invoice_template_products');
    }
};
