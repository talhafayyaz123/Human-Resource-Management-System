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
        Schema::create('sale_offer_components', function (Blueprint $table) {
            $table->id();

            $table->enum("type", ["license", "maintenance", "service", "application", "hosting"]); //License, maintanence, service, application management, hosting/cloud
            $table->unsignedBigInteger("product_id");
            $table->integer("quantity")->default(1);
            $table->integer("hourly_rate")->nullable();
            $table->integer("sale_price")->nullable();
            $table->integer("maintenance_rate")->nullable();
            $table->integer("tax");
            $table->integer("discount");
            $table->integer("payment_period")->nullable();
            $table->json("products")->nullable();

            $table->unsignedBigInteger('product_category_id')->nullable();
            $table->foreign('product_id')->nullable()->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('sale_offer_id')->references('id')->on('sale_offers')->onDelete('cascade');

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
        Schema::dropIfExists('sale_offer_components');
    }
};
