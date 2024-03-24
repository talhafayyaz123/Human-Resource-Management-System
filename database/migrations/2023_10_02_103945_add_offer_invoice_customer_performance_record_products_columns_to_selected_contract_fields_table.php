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
            if (!Schema::hasColumn('selected_contract_fields', 'customer')) {
                $table->foreignUuid('customer')->nullable()->references('id')->on('companies')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('selected_contract_fields', 'invoice')) {
                $table->foreignId('invoice')->nullable()->references('id')->on('invoices')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('selected_contract_fields', 'performance_record')) {
                $table->foreignId('performance_record')->nullable()->references('id')->on('performance_records')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('selected_contract_fields', 'offer')) {
                $table->foreignId('offer')->nullable()->references('id')->on('sale_offers')->cascadeOnDelete();
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
            if (Schema::hasColumn('selected_contract_fields', 'customer')) {
                $table->dropForeign(['customer']);
                $table->dropColumn(['customer']);
            }
            if (Schema::hasColumn('selected_contract_fields', 'invoice')) {
                $table->dropForeign(['invoice']);
                $table->dropColumn(['invoice']);
            }
            if (Schema::hasColumn('selected_contract_fields', 'performance_record')) {
                $table->dropForeign(['performance_record']);
                $table->dropColumn(['performance_record']);
            }
            if (Schema::hasColumn('selected_contract_fields', 'offer')) {
                $table->dropForeign(['offer']);
                $table->dropColumn(['offer']);
            }
        });
    }
};
