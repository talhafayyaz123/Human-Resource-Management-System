<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalRequestApprover extends Model
{
    use HasFactory;

    protected $fillable = ['approver_id', 'type'];

    public function approver()
    {
        return $this->belongsTo(UserProfileData::class, 'approver_id');
    }

    public function manager()
    {
        return $this->where('type', 'manager')->first();
    }

    public function management()
    {
        return $this->where('type', 'management')->get();
    }

    public function executiveManagement()
    {
        return $this->where('type', 'executive-management')->get();
    }

    public function personalRequirement()
    {
        return $this->hasOne(PersonalRequestApprover::class, 'approver_id');
    }
}
