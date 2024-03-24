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
        Schema::table('sale_offers', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_offers', 'is_visible_for_customer')) {
                $table->boolean('is_visible_for_customer')->default(false);
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
        Schema::table('sale_offers', function (Blueprint $table) {
            if (Schema::hasColumn('sale_offers', 'is_visible_for_customer')) {
                $table->dropColumn('is_visible_for_customer');
            }
        });
    }
};
