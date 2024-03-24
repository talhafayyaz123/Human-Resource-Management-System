<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('sale_offer_components', function (Blueprint $table) {
            if(!Schema::hasColumn('sale_offer_components', 'total_netto')){
                $table->decimal('total_netto', 16,2)->after('discount')->nullable();
            }
            if (!Schema::hasColumn('sale_offer_components', 'total_amount')){
                $table->decimal('total_amount',16,2)->after('total_netto')->nullable();
            }
        });
    }


    public function down()
    {
        Schema::table('sale_offer_components', function (Blueprint $table) {
            if (Schema::hasColumn('sale_offer_components', 'total_netto')){
                $table->dropColumn('total_netto');

            }
            if (!Schema::hasColumn('sale_offer_components', 'total_amount')){
                $table->dropColumn('total_amount');
            }

        });
    }
};
