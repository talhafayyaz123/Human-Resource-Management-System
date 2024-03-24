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
            if (!Schema::hasColumn('employee_vacations', 'take_from_overtime')){
                $table->decimal('take_from_overtime')->default(0.00)->after('special_leave_comments')->comment("in hours");
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
            if (Schema::hasColumn('employee_vacations', 'take_from_overtime')){
                $table->dropColumn('take_from_overtime');
            }
        });
    }
};
