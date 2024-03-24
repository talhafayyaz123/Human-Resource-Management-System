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
            $table->after('driver_id', function ($table) {
                $table->enum('car_type', ['rent_car', 'pool_car', 'leasing_car'])->nullable();
                $table->string('asset_number')->nullable();
            });
            $table->string('driver_id')->nullable()->change();
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
            $table->dropColumn('car_type');
            $table->dropColumn('asset_number');
        });
    }
};
