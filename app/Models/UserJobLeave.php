<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserJobLeave extends BaseModel
{
    protected $table = 'user_job_leaves';

    protected $fillable = ['user_job_id', 'total_annual_leaves', 'additional_leaves', 'availed_leaves', 'leave_year', 'is_expired'];
}
