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
        Schema::table('application_management_services', function (Blueprint $table) {
            if (!Schema::hasColumn('application_management_services', 'attachment_id')) {
                $table->foreignId('attachment_id')->nullable()->references('id')->on('contract_attachment')->nullOnDelete();
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
        Schema::table('application_management_services', function (Blueprint $table) {
            if (Schema::hasColumn('application_management_services', 'attachment_id')) {
                $table->dropForeign(['attachment_id']);
                $table->dropColumn('attachment_id');
            }
        });
    }
};
