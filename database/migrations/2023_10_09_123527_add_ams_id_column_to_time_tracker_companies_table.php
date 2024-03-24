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
        Schema::table('time_tracker_companies', function (Blueprint $table) {
            if (!Schema::hasColumn('time_tracker_companies', 'ams_id')) {
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
        Schema::table('time_tracker_companies', function (Blueprint $table) {
            if (Schema::hasColumn('time_tracker_companies', 'ams_id')) {
                $table->dropForeign(['ams_id']);
                $table->dropColumn(['ams_id']);
            }
        });
    }
};
