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
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'software_id')) {
                $table->foreignId('software_id')->nullable()->references('id')->on('product_software')->nullOnDelete();
            }
            if (!Schema::hasColumn('tickets', 'ams_id')) {
                $table->foreignId('ams_id')->nullable()->references('id')->on('application_management_services')->nullOnDelete();
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
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'software_id')) {
                $table->dropForeign(['software_id']);
                $table->dropColumn('software_id');
            }
            if (Schema::hasColumn('tickets', 'ams_id')) {
                $table->dropForeign(['ams_id']);
                $table->dropColumn('ams_id');
            }
        });
    }
};
