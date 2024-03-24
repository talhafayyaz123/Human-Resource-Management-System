<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceRecordEntry extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function timeTrackerCompany()
    {
        return $this->belongsTo(TimeTrackerCompany::class, 'company_timetracker_id');
    }

    public function performanceRecord()
    {
        return $this->belongsTo(PerformanceRecord::class, 'performance_record_id');
    }
}
