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
        Schema::table('fleet_cars', function (Blueprint $table) {
            if (Schema::hasColumn('fleet_cars', 'fleet_driver_id')) {
                $table->dropColumn('fleet_driver_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fleet_cars', function (Blueprint $table) {
            if (!Schema::hasColumn('fleet_cars', 'fleet_driver_id')) {
                $table->unsignedBigInteger('fleet_driver_id');
                $table->foreign('fleet_driver_id')->references('id')->on('fleet_drivers');
            }
        });
    }
};
