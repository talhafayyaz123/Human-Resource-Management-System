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
            if (!Schema::hasColumn('sale_offer_components', 'additional_description')) {
                $table->string('additional_description')->nullable();
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
            if (Schema::hasColumn('sale_offer_components', 'additional_description')) {
                $table->dropColumn('additional_description');
            }
        });
    }
};
