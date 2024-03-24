<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWorkingHour extends BaseModel
{
    use HasFactory;
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    /**
     * Relationship of user profile with job
     */
    public function jobData()
    {
        return $this->belongsTo(UserJobData::class, 'user_job_id');
    }
}
