<?php

use App\Constants;
use App\Models\VehicleClass;
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
        Schema::create('vehicle_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        foreach (Constants::VEHICLE_CLASSES as $data) {
            if (isset($data['name'])) {
                $vehicle_class = new VehicleClass;
                $vehicle_class->name = $data['name'];
                $vehicle_class->save();
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
        Schema::dropIfExists('vehicle_classes');
    }
};
