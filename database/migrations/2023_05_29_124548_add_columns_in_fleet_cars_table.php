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
                $table->json('leasing');
                $table->json('damage_protection');
                $table->json('maintenance_and_abstraction');
                $table->string('color');
                $table->string('kilowatt');
                $table->string('model_key');
                $table->string('model_detail');
                $table->text('extra_equipment');
                $table->string('contact_number');
                $table->string('delivery_date');
                $table->integer('contract_period_months');
            });
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
            $table->dropColumn('leasing');
            $table->dropColumn('damage_protection');
            $table->dropColumn('maintenance_and_abstraction');
            $table->dropColumn('color');
            $table->dropColumn('kilowatt');
            $table->dropColumn('model_key');
            $table->dropColumn('model_detail');
            $table->dropColumn('extra_equipment');
            $table->dropColumn('contact_number');
            $table->dropColumn('delivery_date');
            $table->dropColumn('contract_period_months');
        });
    }
};
