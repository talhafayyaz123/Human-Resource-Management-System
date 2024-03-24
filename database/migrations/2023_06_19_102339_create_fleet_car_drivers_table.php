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
        Schema::create('fleet_car_drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fleet_car_id');
            $table->unsignedBigInteger('fleet_driver_id');
            //foreign keys
            $table->foreign('fleet_driver_id')->references('id')->on('fleet_drivers')->onDelete('cascade');
            $table->foreign('fleet_car_id')->references('id')->on('fleet_cars')->onDelete('cascade');
            $table->softDeletes();
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

        Schema::dropIfExists('fleet_car_drivers');
    }
};
