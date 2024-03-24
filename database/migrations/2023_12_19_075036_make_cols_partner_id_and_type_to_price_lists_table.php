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
        Schema::table('price_lists', function (Blueprint $table) {
            //
            $table->foreignUuid('partner_id')->nullable()->references('id')->on('companies')->nullOnDelete();
            $table->enum('type', ['docshero_customer_price_list', 'docshero_partner_price_list', 'partner_price_list'])->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_lists', function (Blueprint $table) {
            //
            $table->dropForeign(['partner_id']);
            $table->dropColumn(['partner_id']);
            $table->dropColumn(['type']);

        });
    }
};