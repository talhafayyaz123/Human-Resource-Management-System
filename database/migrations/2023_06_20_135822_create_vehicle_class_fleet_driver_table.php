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
        Schema::create('vehicle_class_fleet_driver', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fleet_driver_id');
            $table->unsignedBigInteger('vehicle_class_id');
            //foreign keys
            $table->foreign('fleet_driver_id')->references('id')->on('fleet_drivers')->onDelete('cascade');
            $table->foreign('vehicle_class_id')->references('id')->on('vehicle_classes')->onDelete('cascade');
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
        Schema::dropIfExists('vehicle_class_fleet_driver');
    }
};
