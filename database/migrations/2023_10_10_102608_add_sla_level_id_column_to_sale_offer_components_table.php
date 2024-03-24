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
            if (!Schema::hasColumn('sale_offer_components', 'sla_level_id')) {
                $table->foreignId('sla_level_id')->nullable()->references('id')->on('sla_levels')->nullOnDelete();
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
            if (Schema::hasColumn('sale_offer_components', 'sla_level_id')) {
                $table->dropForeign(['sla_level_id']);
                $table->dropColumn('sla_level_id');
            }
        });
    }
};
