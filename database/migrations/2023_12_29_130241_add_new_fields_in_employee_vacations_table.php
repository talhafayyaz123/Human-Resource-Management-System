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
        Schema::table('employee_vacations', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_vacations', 'vacation_status')){
                $table->string('vacation_status')->nullable();
            }

            if (!Schema::hasColumn('employee_vacations', 'cancel_reason')){
                $table->longText('cancel_reason')->nullable();
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
        Schema::table('employee_vacations', function (Blueprint $table) {
            if (Schema::hasColumn('employee_vacations', 'vacation_status')){
                $table->dropColumn('vacation_status');
            }

            if (Schema::hasColumn('employee_vacations', 'cancel_reason')){
                $table->dropColumn('cancel_reason');
            }
        });
    }
};
