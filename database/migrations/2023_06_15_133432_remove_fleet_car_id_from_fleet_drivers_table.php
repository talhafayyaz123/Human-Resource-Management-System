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
            $table->dropForeign(['fleet_car_id']);
            $table->dropColumn('fleet_car_id');
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
            //
        });
    }
};
