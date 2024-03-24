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
        Schema::create('contract_attachment_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_attachment_id')->references('id')->on('contract_attachment')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->nullOnDelete();
            $table->string('type');
            $table->decimal('quantity')->default(1);
            $table->decimal('hourly_rate')->nullable();
            $table->decimal('sale_price')->nullable();
            $table->decimal('maintenance_price')->nullable();
            $table->decimal('tax')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('total_netto')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->foreignId('payment_period')->nullable()->references('id')->on('payment_periods')->nullOnDelete();
            $table->foreignId('product_category_id')->nullable()->references('id')->on('product_categories')->nullOnDelete();
            $table->decimal('price_per_period')->nullable();
            $table->decimal('duration')->nullable();
            $table->longText('additional_description')->nullable();
            $table->foreignId('sla_service_time_id')->nullable()->references('id')->on('sla_service_times')->nullOnDelete();
            $table->foreignId('sla_level_incident_id')->nullable()->references('id')->on('sla_level_incidents')->nullOnDelete();
            $table->foreignId('sla_level_change_id')->nullable()->references('id')->on('sla_level_changes')->nullOnDelete();
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
        Schema::dropIfExists('contract_attachment_products');
    }
};
