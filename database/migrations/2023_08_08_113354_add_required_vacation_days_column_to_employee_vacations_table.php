<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EmployeeVacation;
use Carbon\Carbon;

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
            if (!Schema::hasColumn('employee_vacations', 'required_vacation_days')) {
                $table->decimal('required_vacation_days')->after('end_date');
            }
        });

        Schema::table('employee_vacations', function () {
            if (Schema::hasColumn('employee_vacations', 'required_vacation_days')) {
                $vacations = EmployeeVacation::get(); // get all the employee vacations
                // loop over all the employee vacations and calculate the difference between the start_date and the end_date
                // and set the required_vacation_days to the calculated difference
                foreach ($vacations as $vacation) {
                    // calculate the difference between the start_date and end_date and ignore all the weekends in between
                    $required_vacation_days = Carbon::parse($vacation->start_date)->diffInDaysFiltered(function ($date) {
                        return !$date->isWeekend();
                    }, Carbon::parse($vacation->end_date)) + 1;
                    $vacation->required_vacation_days = $required_vacation_days;
                    $vacation->save();
                }
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
            if (Schema::hasColumn('employee_vacations', 'required_vacation_days')) {
                $table->dropColumn('required_vacation_days');
            }
        });
    }
};
