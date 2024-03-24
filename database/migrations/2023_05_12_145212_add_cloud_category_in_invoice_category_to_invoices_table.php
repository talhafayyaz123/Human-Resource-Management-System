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
        Schema::table('invoice_category_to_invoices', function (Blueprint $table) {
            DB::statement("ALTER TABLE invoices MODIFY COLUMN invoice_category enum('license', 'maintenance', 'service', 'ams', 'hosting', 'cloud-software')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_category_to_invoices', function (Blueprint $table) {
            //
        });
    }
};
