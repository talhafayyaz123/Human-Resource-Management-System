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
        Schema::table('product_invoice', function (Blueprint $table) {
            $table->decimal('hourly_rate')->nullable()->change();
            $table->decimal('service_hours')->nullable()->change();
            $table->decimal('discount')->nullable()->change();
            $table->decimal('price_per_period')->default(0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_invoice', function (Blueprint $table) {
            $table->integer("hourly_rate")->nullable()->change();
            $table->integer('discount')->nullable()->change();
            $table->integer('price_per_period')->default(0)->nullable()->change();
            $table->integer('service_hours')->nullable()->change();
        });
    }
};
