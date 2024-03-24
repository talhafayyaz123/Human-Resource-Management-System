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
            if (!Schema::hasColumn("sale_offer_components", "parent_component_id")) {
                $table->unsignedBigInteger("parent_component_id")->nullable();
                $table->foreign("parent_component_id")->references("id")->on("sale_offer_components")->onDelete("cascade")->nullable();
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
            if (Schema::hasColumn("sale_offer_components", "parent_component_id")) {
                $table->dropForeign(["parent_component_id"]);
                $table->dropColumn("parent_component_id");
            }
        });
    }
};
