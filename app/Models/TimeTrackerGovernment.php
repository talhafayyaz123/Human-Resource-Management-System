<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTrackerGovernment extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        "type",
        "description",
        "start_time",
        "end_time",
        "date",
        "user_id",
        "is_deletable",
        "time_tracker_company_id",
        'internal_note',
        'employee_vacation_id'
    ];

    public function timeTrackerCompany()
    {
        return $this->belongsTo(TimeTrackerCompany::class, 'time_tracker_company_id');
    }

    public function customer()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * one to many relation with employee_vacations
     */
    public function employeeVacation()
    {
        return $this->belongsTo(EmployeeVacation::class, 'employee_vacation_id');
    }
}
