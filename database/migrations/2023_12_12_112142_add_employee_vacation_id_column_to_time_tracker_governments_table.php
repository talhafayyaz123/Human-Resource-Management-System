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
        Schema::table('time_tracker_governments', function (Blueprint $table) {
            if (!Schema::hasColumn('time_tracker_governments', 'employee_vacation_id')) {
                $table->foreignUuid('employee_vacation_id')->nullable()->references('id')->on('employee_vacations')->cascadeOnDelete();
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
        Schema::table('time_tracker_governments', function (Blueprint $table) {
            if (Schema::hasColumn('time_tracker_governments', 'employee_vacation_id')) {
                $table->dropForeign(['employee_vacation_id']);
                $table->dropColumn(['employee_vacation_id']);
            }
        });
    }
};
