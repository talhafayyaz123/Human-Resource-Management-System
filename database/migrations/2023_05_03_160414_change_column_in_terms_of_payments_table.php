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
        Schema::table('terms_of_payments', function (Blueprint $table) {
            $table->text('offer_text')->nullable()->change();
            $table->text('order_text')->nullable()->change();
            $table->text('invoice_text')->nullable()->change();
            $table->text('payment_terms')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terms_of_payments', function (Blueprint $table) {
            //
        });
    }
};
