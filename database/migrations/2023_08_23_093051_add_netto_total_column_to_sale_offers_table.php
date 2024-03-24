<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SaleOffer;

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
            if (!Schema::hasColumn('sale_offers', 'netto_total')) {
                $table->decimal('netto_total')->default(0);
            }
        });

        Schema::table('sale_offers', function (Blueprint $table) {
            if (Schema::hasColumn('sale_offers', 'netto_total')) {
                $sale_offers = SaleOffer::all();
                foreach ($sale_offers as $sale_offer) {
                    $sale_offer->netto_total = $sale_offer->components->sum('total_netto');
                    $sale_offer->save();
                }
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
            if (Schema::hasColumn('sale_offers', 'netto_total')) {
                $table->dropColumn('netto_total');
            }
        });
    }
};
