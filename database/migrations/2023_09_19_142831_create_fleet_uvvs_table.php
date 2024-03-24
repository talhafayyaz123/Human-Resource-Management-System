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
        Schema::create('fleet_uvvs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('next_date')->nullable();
            $table->string('manager_id');
            $table->unsignedBigInteger('fleet_car_id');
            $table->foreign('fleet_car_id')->references('id')->on('fleet_cars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fleet_uvvs');
    }
};
