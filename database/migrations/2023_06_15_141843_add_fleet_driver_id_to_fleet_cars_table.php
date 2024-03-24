<?php

use App\Models\FleetCar;
use App\Models\FleetDriver;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Facades\App\Services\FleetManagement\FleetDriverService;

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
            if (Schema::hasColumn('fleet_cars', 'fleet_driver_id') == false) {
                $table->unsignedBigInteger('fleet_driver_id')->nullable();
            }
        });
        $fleet_cars = FleetCar::all();
        foreach ($fleet_cars as $fleet_car) {
            if (isset($fleet_car->driver_id)) {
                $fleet_driver = FleetDriver::firstOrCreate(['user_id' => $fleet_car->driver_id]);
                $fleet_car->fleet_driver_id = $fleet_driver->id;
                $fleet_car->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fleet_cars', function (Blueprint $table) {
            if (Schema::hasColumn('fleet_cars', 'fleet_driver_id')) {
                $table->dropForeign(['fleet_driver_id']);
                $table->dropColumn('fleet_driver_id');
            }
        });
    }
};
