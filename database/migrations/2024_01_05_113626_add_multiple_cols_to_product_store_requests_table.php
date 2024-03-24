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
        Schema::table('product_store_requests', function (Blueprint $table) {
            // 
            $table->integer('discount')->nullable()->after('sell_price');
            $table->integer('partner_price')->nullable()->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_store_requests', function (Blueprint $table) {
            //
            $table->dropColumn('discount');
            $table->dropColumn('partner_price');
        });
    }
};