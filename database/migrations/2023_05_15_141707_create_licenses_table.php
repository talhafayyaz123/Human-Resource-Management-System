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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();

            $table->string('company_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('sale_offer_component_id')->nullable();
            $table->integer("quantity")->default(1);
            $table->integer("sale_price")->nullable();
            $table->integer("maintenance_rate")->nullable();

            //Add foreign key relationship
            $table->foreign('company_id')->on('companies')->references('id')->onDelete('cascade');
            $table->foreign('product_id')->on('products')->references('id')->onDelete('cascade');
            $table->foreign('sale_offer_component_id')->on('sale_offer_components')->references('id')->onDelete('cascade');

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
        Schema::dropIfExists('licenses');
    }
};
