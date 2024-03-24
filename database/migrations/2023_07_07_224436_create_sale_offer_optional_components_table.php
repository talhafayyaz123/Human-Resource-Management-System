<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('sale_offer_optional_components', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['license', 'maintenance', 'service', 'application', 'hosting', 'cloud']);
            $table->unsignedBigInteger("product_id");
            $table->integer("quantity")->default(1);
            $table->integer("hourly_rate")->nullable();
            $table->integer("sale_price")->nullable();
            $table->integer("maintenance_rate")->nullable();
            $table->integer("tax");
            $table->integer("discount");
            $table->decimal('total_netto', 16,2)->nullable();
            $table->decimal('total_amount', 16,2)->nullable();
            $table->integer('payment_period')->nullable();
            $table->longText("products")->nullable();
            $table->unsignedBigInteger('product_category_id')->nullable();
            $table->foreign('product_id')->nullable()->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('sale_offer_id')->references('id')->on('sale_offers')->onDelete('cascade');
            $table->integer('price_per_period')->default(0)->nullable();
            $table->decimal('duration', 8,2);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('sale_offer_optional_components');
    }
};
