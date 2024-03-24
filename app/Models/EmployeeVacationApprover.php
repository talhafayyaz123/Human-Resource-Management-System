<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeVacationApprover extends BaseModel
{
    use HasFactory;

    protected $table = 'employee_vacation_approver';

    public $timestamps  = false;

    /**
     * Relation to which report
     */
    public function employeeVacations()
    {
        return $this->belongsTo(EmployeeVacation::class, 'employee_vacation_id');
    }
}
