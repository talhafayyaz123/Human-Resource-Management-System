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
        Schema::table('sale_offer_components', function (Blueprint $table) {
            $table->decimal('price_per_period')->default(0)->nullable()->change();
            $table->decimal("sale_price")->nullable()->change();
            $table->decimal("tax")->change();
            $table->decimal("hourly_rate")->nullable()->change();
            $table->decimal('maintenance_rate')->nullable()->change();
            $table->decimal('discount')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_offer_components', function (Blueprint $table) {
            $table->integer('price_per_period')->default(0)->nullable()->change();
            $table->integer("sale_price")->nullable()->change();
            $table->integer("tax")->change();
            $table->integer("hourly_rate")->nullable()->change();
            $table->integer('maintenance_rate')->nullable()->change();
            $table->integer('discount')->nullable()->change();
        });
    }
};
