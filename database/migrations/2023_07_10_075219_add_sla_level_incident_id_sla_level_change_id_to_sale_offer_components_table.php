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
        Schema::table('sale_offer_components', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_offer_components', 'sla_level_incident_id')) {
                $table->unsignedBigInteger('sla_level_incident_id')->nullable();
                $table->foreign('sla_level_incident_id')->references('id')->on('sla_level_incidents')->onDelete('set null');
            }
            if (!Schema::hasColumn('sale_offer_components', 'sla_level_change_id')) {
                $table->unsignedBigInteger('sla_level_change_id')->nullable();
                $table->foreign('sla_level_change_id')->references('id')->on('sla_level_changes')->onDelete('set null');
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
        Schema::table('sale_offer_components', function (Blueprint $table) {
            if (Schema::hasColumn('sale_offer_components', 'sla_level_incident_id')) {
                $table->dropForeign(['sla_level_incident_id']);
                $table->dropColumn('sla_level_incident_id');
            }
            if (Schema::hasColumn('sale_offer_components', 'sla_level_change_id')) {
                $table->dropForeign(['sla_level_change_id']);
                $table->dropColumn('sla_level_change_id');
            }
        });
    }
};
