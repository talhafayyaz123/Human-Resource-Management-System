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
        Schema::table('partner_order_products', function (Blueprint $table) {
            if (!Schema::hasColumn('partner_order_products', 'partner_price')){
                $table->decimal('partner_price')->nullable()->after('discount');
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
        Schema::table('partner_order_products', function (Blueprint $table) {
            if (Schema::hasColumn('partner_order_products', 'partner_price')){
                $table->dropColumn('partner_price');
            }
        });
    }
};
