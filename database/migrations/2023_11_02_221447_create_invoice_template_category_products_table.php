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
        Schema::create('invoice_template_category_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_template_id')->references('id')->on('invoice_templates')->cascadeOnDelete();
            $table->longText('product_ids')->nullable();
            $table->foreignId('category_id')->references('id')->on('product_categories')->cascadeOnDelete();
            $table->decimal('quantity')->nullable();
            $table->decimal('hourly_rate')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('tax')->nullable();
            $table->decimal('tax_rate')->nullable();
            $table->decimal('netto_total')->nullable();
            $table->longText('additional_description')->nullable();
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
        Schema::dropIfExists('invoice_template_category_products');
    }
};
