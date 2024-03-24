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
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn("companies", "sla_infrastructure")) {
                $table->unsignedBigInteger("sla_infrastructure")->nullable();
                $table->foreign("sla_infrastructure")->references("id")->on("sla_infrastructures")->onDelete("set null");
            }
            if (!Schema::hasColumn("companies", "sla_level")) {
                $table->unsignedBigInteger("sla_level")->nullable();
                $table->foreign("sla_level")->references("id")->on("sla_levels")->onDelete("set null");
            }
            if (!Schema::hasColumn("companies", "sla_service_time")) {
                $table->unsignedBigInteger("sla_service_time")->nullable();
                $table->foreign("sla_service_time")->references("id")->on("sla_service_times")->onDelete("set null");
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
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(["sla_infrastructure"]);
            $table->dropColumn("sla_infrastructure");
            $table->dropForeign(["sla_level"]);
            $table->dropColumn("sla_level");
            $table->dropForeign(["sla_service_time"]);
            $table->dropColumn("sla_service_time");
        });
    }
};
