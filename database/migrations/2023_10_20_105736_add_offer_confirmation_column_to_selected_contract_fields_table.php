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
        Schema::table('selected_contract_fields', function (Blueprint $table) {
            if (!Schema::hasColumn('selected_contract_fields', 'offer_confirmation')) {
                $table->foreignId('offer_confirmation')->nullable()->references('id')->on('sale_offers')->nullOnDelete();
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
        Schema::table('selected_contract_fields', function (Blueprint $table) {
            if (Schema::hasColumn('selected_contract_fields', 'offer_confirmation')) {
                $table->dropForeign(['offer_confirmation']);
                $table->dropColumn(['offer_confirmation']);
            }
        });
    }
};
