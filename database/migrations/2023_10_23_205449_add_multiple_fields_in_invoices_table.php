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
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('is_invoice_template', 'invoices')) {
                $table->boolean('is_invoice_template')->default(false);
            }

            if (!Schema::hasColumn('next_create_date', 'invoices')) {
                $table->date('next_create_date')->nullable()->comment("for invoice template");
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
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('is_invoice_template', 'invoices')) {
                $table->dropColumn('is_invoice_template');
            }

            if (Schema::hasColumn('next_create_date', 'invoices')) {
                $table->dropColumn('next_create_date');
            }
        });
    }
};
