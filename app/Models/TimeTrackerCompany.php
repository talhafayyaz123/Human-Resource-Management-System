<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTrackerCompany extends BaseModel
{
    use HasFactory;

    /**
     * Association with company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get all of the models.
     */
    public function module()
    {
        return $this->morphTo(__FUNCTION__, 'module_type', 'module_id');
    }

    /**
     * Many to one relational logic of goverment to company
     */
    public function governments()
    {
        return $this->hasMany(TimeTrackerGovernment::class, 'time_tracker_company_id');
    }

    public function performanceRecord()
    {
        return $this->belongsTo(PerformanceRecord::class, 'performance_record_id');
    }

    public function user()
    {
        return $this->belongsTo(UserProfileData::class, 'user_id', 'user_id');
    }

    //an eloquent relation for sorting
    public function userProfileData()
    {
        return $this->belongsTo(UserProfileData::class, 'user_id', 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
