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
        Schema::create('fleet_cars', function (Blueprint $table) {
            $table->id();
            $table->string('licence_number');
            $table->string('vim');
            $table->string('brand');
            $table->string('model');
            $table->enum('fuel_type', ['diesel', 'electronic']);
            $table->decimal('leasing_rate');
            $table->timestamp('leasing_start_date')->useCurrent();
            $table->timestamp('leasing_end_date')->useCurrent();
            $table->decimal('miles');
            $table->decimal('total_mileage');
            $table->enum('status', ['ready', 'out_of_service']);
            $table->string('driver_id');
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
        Schema::dropIfExists('fleet_cars');
    }
};
