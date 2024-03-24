<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            if (Schema::hasColumn('sale_offers', 'receiver_type'))
                DB::statement("ALTER TABLE sale_offers MODIFY COLUMN receiver_type enum('customer', 'lead', 'partner')");
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
            if (Schema::hasColumn('sale_offers', 'receiver_type'))
                DB::statement("ALTER TABLE sale_offers MODIFY COLUMN receiver_type enum('customer', 'lead')");
        });
    }
};
