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
        Schema::table('fleet_management_interval_settings', function (Blueprint $table) {
            $table->enum('interval_setting_type', ['licence', 'uvv', 'fuel', 'cost'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fleet_management_interval_settings', function (Blueprint $table) {
            $table->dropColumn('interval_setting_type');
        });
    }
};
