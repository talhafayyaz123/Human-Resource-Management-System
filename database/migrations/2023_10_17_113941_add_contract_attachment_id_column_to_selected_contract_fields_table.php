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
            if (!Schema::hasColumn('selected_contract_fields', 'contract_attachment_id')) {
                $table->foreignId('contract_attachment_id')->nullable()->references('id')->on('contract_attachment')->nullOnDelete();
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
            if (Schema::hasColumn('selected_contract_fields', 'contract_attachment_id')) {
                $table->dropForeign(['contract_attachment_id']);
                $table->dropColumn(['contract_attachment_id']);
            }
        });
    }
};
