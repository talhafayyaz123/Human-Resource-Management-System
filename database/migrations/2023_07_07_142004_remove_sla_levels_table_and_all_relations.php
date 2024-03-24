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

        // rename the sla_level_entries table to sla_level_incidents
        if (Schema::hasTable('sla_level_entries')) {
            Schema::table('sla_level_entries', function (Blueprint $table) {
                $table->dropForeign(['sla_level_id']);
                $table->dropColumn('sla_level_id');
                $table->dropColumn('change');
                $table->string("name");
            });

            Schema::rename('sla_level_entries', 'sla_level_incidents');
        }

        // drop the relations of sla_levels modules from companies
        Schema::table("companies", function (Blueprint $table) {
            $table->dropForeign(["sla_infrastructure"]);
            $table->dropColumn("sla_infrastructure");
            $table->dropForeign(["sla_level"]);
            $table->dropColumn("sla_level");
            $table->dropForeign(["sla_service_time"]);
            $table->dropColumn("sla_service_time");
        });

        // drop the sla_levels relation from sale_offer_components
        Schema::table("sale_offer_components", function (Blueprint $table) {
            $table->dropForeign(["sla_level_id"]);
            $table->dropColumn("sla_level_id");
        });

        // drop the sla_levels table after all the relations have been dropped
        Schema::dropIfExists('sla_levels');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        // add the sla_levels table
        if (!Schema::hasTable("sla_levels")) {
            Schema::create('sla_levels', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        // rename the sla_level_incidents to sla_level_entries and add the relations to sla_levels
        if (Schema::hasTable('sla_level_incidents')) {
            Schema::rename('sla_level_incidents', 'sla_level_entries');

            Schema::table('sla_level_entries', function (Blueprint $table) {
                $table->unsignedBigInteger('sla_level_id');
                $table->foreign('sla_level_id')->references('id')->on('sla_levels')->onDelete('cascade');
                $table->string("change")->nullable();
                $table->dropColumn("name");
            });
        }

        // add the sla_levels relations to companies
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

        // add the sla_levels relations to sale_offer_components
        Schema::table('sale_offer_components', function (Blueprint $table) {
            if (!Schema::hasColumn("sale_offer_components", "sla_level_id")) {
                $table->unsignedBigInteger("sla_level_id")->nullable();
                $table->foreign("sla_level_id")->references("id")->on("sla_levels")->onDelete("set null")->nullable();
            }
        });
    }
};
