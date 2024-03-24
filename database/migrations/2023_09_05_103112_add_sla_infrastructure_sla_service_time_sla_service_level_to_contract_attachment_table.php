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
        Schema::table('contract_attachment', function (Blueprint $table) {
            if (!Schema::hasColumn('contract_attachment', 'sla_infrastructure_id'))
                $table->foreignId('sla_infrastructure_id')->nullable()->references('id')->on('sla_infrastructures')->cascadeOnDelete();
            if (!Schema::hasColumn('contract_attachment', 'sla_infrastructure_user_support'))
                $table->decimal('sla_infrastructure_user_support')->nullable();
            if (!Schema::hasColumn('contract_attachment', 'sla_service_time_id'))
                $table->foreignId('sla_service_time_id')->nullable()->references('id')->on('sla_service_times')->cascadeOnDelete();
            if (!Schema::hasColumn('contract_attachment', 'sla_level_incident_id'))
                $table->foreignId('sla_level_incident_id')->nullable()->references('id')->on('sla_level_incidents')->cascadeOnDelete();
            if (!Schema::hasColumn('contract_attachment', 'sla_level_change_id'))
                $table->foreignId('sla_level_change_id')->nullable()->references('id')->on('sla_level_changes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_attachment', function (Blueprint $table) {
            if (Schema::hasColumn('contract_attachment', 'sla_infrastructure_id')) {
                $table->dropForeign(['sla_infrastructure_id']);
                $table->dropColumn('sla_infrastructure_id');
            }
            if (Schema::hasColumn('contract_attachment', 'sla_infrastructure_user_support'))
                $table->dropColumn('sla_infrastructure_user_support');
            if (Schema::hasColumn('contract_attachment', 'sla_service_time_id')) {
                $table->dropForeign(['sla_service_time_id']);
                $table->dropColumn('sla_service_time_id');
            }
            if (Schema::hasColumn('contract_attachment', 'sla_level_incident_id')) {
                $table->dropForeign(['sla_level_incident_id']);
                $table->dropColumn('sla_level_incident_id');
            }
            if (Schema::hasColumn('contract_attachment', 'sla_level_change_id')) {
                $table->dropForeign(['sla_level_change_id']);
                $table->dropColumn('sla_level_change_id');
            }
        });
    }
};
