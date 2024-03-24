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
            if (!Schema::hasColumn("sale_offer_components", "sla_infrastructure_id")) {
                $table->unsignedBigInteger("sla_infrastructure_id")->nullable();
                $table->foreign("sla_infrastructure_id")->references("id")->on("sla_infrastructures")->onDelete("set null")->nullable();
            }
            if (!Schema::hasColumn("sale_offer_components", "sla_service_time_id")) {
                $table->unsignedBigInteger("sla_service_time_id")->nullable();
                $table->foreign("sla_service_time_id")->references("id")->on("sla_service_times")->onDelete("set null")->nullable();
            }
            if (!Schema::hasColumn("sale_offer_components", "sla_level_id")) {
                $table->unsignedBigInteger("sla_level_id")->nullable();
                $table->foreign("sla_level_id")->references("id")->on("sla_levels")->onDelete("set null")->nullable();
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
            if (Schema::hasColumn("sale_offer_components", "sla_infrastructure_id")) {
                $table->dropForeign(["sla_infrastructure_id"]);
                $table->dropColumn("sla_infrastructure_id");
            }
            if (Schema::hasColumn("sale_offer_components", "sla_service_time_id")) {
                $table->dropForeign(["sla_service_time_id"]);
                $table->dropColumn("sla_service_time_id");
            }
            if (Schema::hasColumn("sale_offer_components", "sla_level_id")) {
                $table->dropForeign(["sla_level_id"]);
                $table->dropColumn("sla_level_id");
            }
        });
    }
};
