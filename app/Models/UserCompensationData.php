<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCompensationData extends BaseModel
{
    use HasFactory;
    protected $fillable = ['user_id'];

    /**
     * Relationship of user profile with compensation
     */
    public function user()
    {
        return $this->belongsTo(UserProfileData::class, 'user_profile_id');
    }

    public function bonuses()
    {
        return $this->hasMany(CompensationBonus::class, 'compensation_id');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }
}
