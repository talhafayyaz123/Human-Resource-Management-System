<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalRequirement extends Model
{
    use HasFactory;

    protected $fillable = ['personal_request_id', 'approver_id', 'status'];

    public function personalRequest()
    {
        return $this->belongsTo(PersonalRequest::class, 'personal_request_id');
    }

    public function approver()
    {
        return $this->belongsTo(UserProfileData::class, 'approver_id');
    }

    public function scopeSearch($query, $arg)
    {
        return $query->whereHas('personalRequest', function ($query) use ($arg) {
            $query->whereHas('requester', function ($query) use ($arg) {
                $query->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', ['%' . $arg . '%']);
            })->orWhereHas('department', function ($query) use ($arg) {
                $query->where('name', 'LIKE', ['%' . $arg . '%']);
            })->orWhereHas('team', function ($query) use ($arg) {
                $query->where('name', 'LIKE', ['%' . $arg . '%']);
            })->orWhereHas('job', function ($query) use ($arg) {
                $query->where('j_title', 'LIKE', ['%' . $arg . '%']);
            })->orWhereHas('contractType', function ($query) use ($arg) {
                $query->where('name', 'LIKE', ['%' . $arg . '%']);
            })->orWhere('start_date', 'LIKE', ['%' . $arg . '%']);
        });
    }
}
