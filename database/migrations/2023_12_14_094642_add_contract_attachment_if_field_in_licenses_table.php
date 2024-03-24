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
        Schema::table('licenses', function (Blueprint $table) {
            if (!Schema::hasColumn('licenses', 'contract_attachment_id')){
                $table->foreignId('contract_attachment_id')->nullable()->references('id')->on('contract_attachment')->cascadeOnDelete();
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
        Schema::table('licenses', function (Blueprint $table) {
            if (Schema::hasColumn('licenses', 'contract_attachment_id')){
                $table->dropForeign('licenses_contract_attachment_id_foreign');
                $table->dropColumn('contract_attachment_id');
            }
        });
    }
};
