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
        if (Schema::hasColumn('product_invoice', 'price_per_period')==false)
        {
            Schema::table('product_invoice', function (Blueprint $table) {
                $table->integer('price_per_period')->default(0)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_invoice', function (Blueprint $table) {
            //
        });
    }
};
