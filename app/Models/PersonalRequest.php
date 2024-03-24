<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PersonalRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'department_id', 'team_id', 'job_id', 'location_id', 'contract_type_id', 'start_date'];

    public function requester()
    {
        return $this->belongsTo(UserProfileData::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(UserDepartment::class, 'department_id');
    }

    public function team()
    {
        return $this->belongsTo(UserTeam::class, 'team_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function location()
    {
        return $this->belongsTo(UserLocation::class, 'location_id');
    }

    public function contractType()
    {
        return $this->belongsTo(ContractType::class, 'contract_type_id');
    }

    public function timeModel()
    {
        return $this->hasMany(TimeModel::class, 'personal_request_id');
    }

    public function personalRequirements()
    {
        return $this->hasMany(PersonalRequirement::class, 'personal_request_id');
    }

    public function scopeSearch($query, $arg)
    {
        return $query->whereHas('requester', function ($query) use ($arg) {
            $query->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', ['%' . $arg . '%']);
        })->orWhereHas('department', function ($query) use ($arg) {
            $query->where('name', 'LIKE', ['%' . $arg . '%']);
        })->orWhereHas('team', function ($query) use ($arg) {
            $query->where('name', 'LIKE', ['%' . $arg . '%']);
        })->orWhereHas('job', function ($query) use ($arg) {
            $query->where('j_title', 'LIKE', ['%' . $arg . '%']);
        })->orWhereHas('location', function ($query) use ($arg) {
            $query->where('name', 'LIKE', ['%' . $arg . '%']);
        })->orWhereHas('contractType', function ($query) use ($arg) {
            $query->where('name', 'LIKE', ['%' . $arg . '%']);
        })->orWhere('start_date', 'LIKE', ['%' . $arg . '%']);
    }
}
