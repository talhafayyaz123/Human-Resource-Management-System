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
        Schema::create('fuel_receipts', function (Blueprint $table) {
            $table->id();
            $table->timestamp('delivery_date');
            $table->string('actual_mileage');
            $table->enum('product', ['diesel', 'electronic', 'gasoline']);
            $table->integer('quantity');
            $table->string('unit');
            $table->decimal('price_per_unit');
            $table->decimal('total_netto');
            $table->decimal('tax');
            $table->decimal('total_brutto');
            $table->string('manager_id');
            $table->unsignedBigInteger('fleet_car_id');
            $table->foreign('fleet_car_id')->references('id')->on('fleet_cars')->onDelete('cascade');
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
        Schema::table('fuel_receipts', function (Blueprint $table) {
            $table->dropForeign(['fleet_car_id']);
        });

        Schema::dropIfExists('fuel_receipts');
    }
};
