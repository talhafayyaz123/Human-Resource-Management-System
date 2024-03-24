<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserJobData extends BaseModel
{
    use HasFactory;
    protected $fillable = ['user_id'];
    protected $casts = [
        'working_days' => 'array'
    ];

    /**
     * Relationship of user profile with job
     */
    public function user()
    {
        return $this->belongsTo(UserProfileData::class, 'user_profile_id');
    }

    public function workingHours()
    {
        return $this->hasMany(UserWorkingHour::class, 'user_job_id');
    }

    public function location()
    {
        return $this->belongsTo(UserLocation::class, 'user_location_id');
    }

    public function userJobLeave(){
        return $this->hasMany(UserJobLeave::class, 'user_job_id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
    public function userJobDepartments()
    {
        return $this->belongsToMany(UserDepartment::class,'user_job_departments', 'user_job_id', 'department_id');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }
}
