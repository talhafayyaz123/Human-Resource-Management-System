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
        Schema::table('product_invoice_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('product_invoice_categories', 'additional_description')) {
                $table->string('additional_description')->nullable()->after('discount');
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
        Schema::table('product_invoice_categories', function (Blueprint $table) {
            if (Schema::hasColumn('product_invoice_categories', 'additional_description')) {
                $table->dropColumn('additional_description');
            }
        });
    }
};
