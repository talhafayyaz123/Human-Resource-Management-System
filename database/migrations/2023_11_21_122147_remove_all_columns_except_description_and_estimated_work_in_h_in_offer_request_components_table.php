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
        Schema::table('offer_request_components', function (Blueprint $table) {
            if (Schema::hasColumn('offer_request_components', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn(['product_id']);
            }

            if (Schema::hasColumn('offer_request_components', 'quantity')) {
                $table->dropColumn('quantity');
            }
            if (Schema::hasColumn('offer_request_components', 'hourly_rate')) {
                $table->dropColumn('hourly_rate');
            }
            if (Schema::hasColumn('offer_request_components', 'sale_price')) {
                $table->dropColumn('sale_price');
            }
            if (Schema::hasColumn('offer_request_components', 'maintenance_rate')) {
                $table->dropColumn('maintenance_rate');
            }
            if (Schema::hasColumn('offer_request_components', 'tax')) {
                $table->dropColumn('tax');
            }
            if (Schema::hasColumn('offer_request_components', 'discount')) {
                $table->dropColumn('discount');
            }
            if (Schema::hasColumn('offer_request_components', 'total_netto')) {
                $table->dropColumn('total_netto');
            }
            if (Schema::hasColumn('offer_request_components', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if (Schema::hasColumn('offer_request_components', 'payment_period')) {
                $table->dropForeign(['payment_period']);
                $table->dropColumn(['payment_period']);
            }
            if (Schema::hasColumn('offer_request_components', 'products')) {
                $table->dropColumn('products');
            }
            if (Schema::hasColumn('offer_request_components', 'product_category_id')) {
                $table->dropForeign(['product_category_id']);
                $table->dropColumn(['product_category_id']);
            }
         
            if (Schema::hasColumn('offer_request_components', 'price_per_period')) {
                $table->dropColumn('price_per_period');
            }
            if (Schema::hasColumn('offer_request_components', 'duration')) {
                $table->dropColumn('duration');
            }
            if (Schema::hasColumn('offer_request_components', 'sla_service_time_id')) {
                $table->dropForeign(['sla_service_time_id']);
                $table->dropColumn(['sla_service_time_id']);
            }
            if (Schema::hasColumn('offer_request_components', 'sla_level_id')) {
                $table->dropForeign(['sla_level_id']);
                $table->dropColumn(['sla_level_id']);
            }
            if (Schema::hasColumn('offer_request_components', 'parent_component_id')) {
                $table->dropForeign(['parent_component_id']);
                $table->dropColumn(['parent_component_id']);
            }
            if (Schema::hasColumn('offer_request_components', 'additional_description')) {
                $table->dropColumn('additional_description');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_request_components', function (Blueprint $table) {
            if (!Schema::hasColumn('offer_request_components', 'product_id')) {
                $table->foreignId('product_id')->references('id')->on('products')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('offer_request_components', 'quantity')) {
                $table->decimal('quantity');
            }
            if (!Schema::hasColumn('offer_request_components', 'hourly_rate')) {
                $table->decimal('hourly_rate')->nullable();
            }
            if (!Schema::hasColumn('offer_request_components', 'sale_price')) {
                $table->decimal('sale_price')->nullable();
            }
            if (!Schema::hasColumn('offer_request_components', 'maintenance_rate')) {
                $table->decimal('maintenance_rate')->nullable();
            }
            if (!Schema::hasColumn('offer_request_components', 'tax')) {
                $table->decimal('tax');
            }
            if (!Schema::hasColumn('offer_request_components', 'discount')) {
                $table->decimal('discount')->nullable();
            }
            if (!Schema::hasColumn('offer_request_components', 'total_netto')) {

                $table->decimal('total_netto')->nullable();
            }
            if (!Schema::hasColumn('offer_request_components', 'total_amount')) {

                $table->decimal('total_amount')->nullable();
            }
            if (!Schema::hasColumn('offer_request_components', 'payment_period')) {

                $table->foreignId('payment_period')->nullable()->references('id')->on('payment_periods')->nullOnDelete();
            }
            if (!Schema::hasColumn('offer_request_components', 'products')) {
                $table->json('products')->nullable();
            }
            if (!Schema::hasColumn('offer_request_components', 'product_category_id')) {
                $table->foreignId('product_category_id')->nullable()->references('id')->on('product_categories')->nullOnDelete();
            }
        
            if (!Schema::hasColumn('offer_request_components', 'price_per_period')) {
                $table->decimal('price_per_period')->nullable();
            }
            if (!Schema::hasColumn('offer_request_components', 'duration')) {

                $table->decimal('duration')->default(1);
            }
            if (!Schema::hasColumn('offer_request_components', 'sla_service_time_id')) {
                $table->foreignId('sla_service_time_id')->nullable()->references('id')->on('sla_service_times')->nullOnDelete();
            }
            if (!Schema::hasColumn('offer_request_components', 'sla_level_id')) {
                $table->foreignId('sla_level_id')->nullable()->references('id')->on('sla_levels')->nullOnDelete();
            }
            if (!Schema::hasColumn('offer_request_components', 'parent_component_id')) {
                $table->foreignId('parent_component_id')->nullable()->references('id')->on('offer_request_components')->nullOnDelete();
            }
            if (!Schema::hasColumn('offer_request_components', 'additional_description')) {
                $table->string('additional_description')->nullable();
            }
        });
    }
};
