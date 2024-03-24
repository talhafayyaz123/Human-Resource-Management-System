<?php

namespace App\Models;

use App\Helpers\GenerateUuidHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeVacation extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = [
        'replacements' => 'array',
    ];


    /**
     * Get the company that this employee belongs to.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = GenerateUuidHelper::generateUUID();
        });
    }

    /**
     * Relationship of vacations with relevant Doc hero users
     * //Talked to persons
     */
    public function vacationApprovers()
    {
        return $this->belongsToMany(EmployeeVacation::class, 'employee_vacation_approver', 'employee_vacation_id', 'approver_id')->withPivot('status');
    }

    public function employeeVacationApprover()
    {
        return $this->hasMany(EmployeeVacationApprover::class, 'employee_vacation_id');
    }
    public function scopeFilter($query, array $filters)
    {
        $format_for_date = null;
        if (isset($filters['search'])) {
            $format_for_date = str_replace('.', '-', $filters['search']);
            // Define the German date format pattern (dd.mm.YYYY)
            $german_date_format_pattern = '/^\d{2}\-\d{2}\-\d{4}$/';
            if (preg_match($german_date_format_pattern, $format_for_date)) {
                $format_for_date = Carbon::parse($format_for_date)->format('Y-m-d');
            }
        }
        return $query->when($format_for_date ?? null, function ($query, $search) {
            $query->whereDate('start_date', $search)
                ->orWhere('end_date', 'like', '%' . $search . '%')
                ->orWhere('leave_type', 'like', '%' . $search . '%');
        })->when($filters['leaveType'] ?? null, function ($query, $leaveType) {
            $query->where('leave_type', $leaveType);
        })
            ->when($filters['startDate'] ?? null, function ($query, $startDate) {
                $query->whereDate('start_date', ">=", $startDate);
            })
            ->when($filters['endDate'] ?? null, function ($query, $endDate) {
                $query->whereDate('end_date', "<=", $endDate);
            });
    }

    /**
     * one to many relation with time_tracker_governments
     */
    public function timeTrackerGovernments()
    {
        return $this->hasMany(TimeTrackerGovernment::class, 'employee_vacation_id');
    }
}
