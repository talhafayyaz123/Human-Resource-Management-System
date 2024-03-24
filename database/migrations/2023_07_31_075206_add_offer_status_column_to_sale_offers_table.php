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
            if (!Schema::hasColumn('sale_offers', 'offer_status')) {
                $table->enum('offer_status', [
                    'versendet',
                    'beauftragt',
                    'abgelehnt'
                ])->nullable();
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
            if (Schema::hasColumn('sale_offers', 'offer_status')) {
                $table->dropColumn('offer_status');
            }
        });
    }
};
