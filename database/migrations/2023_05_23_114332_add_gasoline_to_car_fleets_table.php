<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            DB::statement("ALTER TABLE fleet_cars MODIFY COLUMN fuel_type enum('diesel', 'electronic', 'gasoline')");
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
            DB::statement("ALTER TABLE fleet_cars MODIFY COLUMN fuel_type ENUM('diesel', 'gasoline')");
        });
    }
};
