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
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fleet_drivers')) {
            Schema::create('fleet_drivers', function (Blueprint $table) {
                $table->id();
                $table->enum('status', ['new', 'ready', 'out_of_service']);
                $table->timestamp('licence_valid_until')->nullable();
                $table->timestamp('licenced_date')->useCurrent();
                $table->json('vehicle_classes')->nullable();
                $table->unsignedBigInteger('fleet_car_id');
                $table->foreign('fleet_car_id')->references('id')->on('fleet_cars')->onDelete('cascade');
                $table->string('user_id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fleet_drivers');
    }
};
