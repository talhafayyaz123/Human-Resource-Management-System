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
            if (!Schema::hasColumn('time_tracker_companies', 'project_id')) {
                $table->foreignId('project_id')->nullable()->references('id')->on('projects')->nullOnDelete();
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
            if (Schema::hasColumn('time_tracker_companies', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn(['project_id']);
            }
        });
    }
};
