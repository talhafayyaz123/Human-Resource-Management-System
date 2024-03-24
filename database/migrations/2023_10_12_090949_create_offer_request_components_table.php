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
        if (!Schema::hasTable('offer_request_components')) {
            Schema::create('offer_request_components', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['license', 'maintenance', 'service', 'application', 'hosting', 'cloud'])->nullable();
                $table->foreignId('product_id')->references('id')->on('products')->cascadeOnDelete();
                $table->decimal('quantity');
                $table->decimal('hourly_rate')->nullable();
                $table->decimal('sale_price')->nullable();
                $table->decimal('maintenance_rate')->nullable();
                $table->decimal('tax');
                $table->decimal('discount')->nullable();
                $table->decimal('total_netto')->nullable();
                $table->decimal('total_amount')->nullable();
                $table->foreignId('payment_period')->nullable()->references('id')->on('payment_periods')->nullOnDelete();
                $table->json('products')->nullable();
                $table->foreignId('product_category_id')->nullable()->references('id')->on('product_categories')->nullOnDelete();
                $table->foreignId('offer_request_id')->references('id')->on('offer_requests')->cascadeOnDelete();
                $table->decimal('price_per_period')->nullable();
                $table->decimal('duration')->default(1);
                $table->foreignId('sla_service_time_id')->nullable()->references('id')->on('sla_service_times')->nullOnDelete();
                $table->foreignId('sla_level_id')->nullable()->references('id')->on('sla_levels')->nullOnDelete();
                $table->foreignId('parent_component_id')->nullable()->references('id')->on('offer_request_components')->nullOnDelete();
                $table->string('additional_description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_request_components');
    }
};
