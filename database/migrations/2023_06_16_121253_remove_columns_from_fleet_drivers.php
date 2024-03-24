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
        Schema::table('fleet_drivers', function (Blueprint $table) {
            if (Schema::hasColumn('fleet_drivers', 'licenced_date')) {
                $table->dropColumn('licenced_date');
            }
            if (Schema::hasColumn('fleet_drivers', 'licence_valid_until')) {
                $table->dropColumn('licence_valid_until');
            }
            if (Schema::hasColumn('fleet_drivers', 'vehicle_classes')) {
                $table->dropColumn('vehicle_classes');
            }
            if (!Schema::hasColumn('fleet_drivers', 'car_type')) {
                $table->after('user_id', function ($table) {
                    $table->enum('car_type', ['rent_car', 'pool_car', 'leasing_car'])->nullable();
                });
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
        Schema::table('fleet_drivers', function (Blueprint $table) {
            $table->dropColumn('car_type');
            $table->timestamp('licenced_date')->nullable();
            $table->timestamp('licence_valid_until')->nullable();
            $table->json('vehicle_classes')->nullable();
        });
    }
};
