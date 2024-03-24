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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->text('article_number', 15);
            $table->string('name', 150);
            $table->boolean('status')->default(0);
            $table->longText('description')->nullable();
            $table->string('listing_price')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('manufacturer_discount')->nullable();
            // $table->string('payment_period')->nullable();
            $table->boolean('recurring_payment')->default(0)->nullable();
            $table->enum('payment_details_type', ['software', 'service', 'pauschal', 'ams'])->nullable();
            $table->string('tax')->nullable();
            $table->string('sale_price')->nullable();
            $table->string('profit')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('maintanence_price')->nullable();
            $table->integer('maintenance_rate')->nullable();
            $table->string('execution_order_number')->nullable();
            $table->string('manufacturer_article_number')->nullable();

            $table->string('hourly_rate')->nullable();
            $table->string('daily_rate')->nullable();

            $table->string('service_hours')->nullable();
            $table->string('service_days')->nullable();

            //Add foreign key relationship
            $table->unsignedBigInteger('product_unit_id')->references('id')->on('product_units')->nullable();
            $table->unsignedBigInteger('product_software_id')->references('id')->on('product_software')->nullable();
            $table->unsignedBigInteger('payment_period_id')->references('id')->on('payment_periods')->nullable();
            //
            $table->unsignedBigInteger('product_group_id')->references('id')->on('product_groups')->onDelete('cascade')->nullable();
            $table->unsignedBigInteger('elo_version_id')->nullable()->references('id')->on('elo_versions')->onDelete('cascade');
            $table->unsignedBigInteger('product_category_id')->references('id')->on('product_categories')->onDelete('cascade')->nullable();


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
        Schema::dropIfExists('products');
    }
};
