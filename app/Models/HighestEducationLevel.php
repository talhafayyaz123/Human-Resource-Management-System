<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighestEducationLevel extends BaseModel
{
    protected $guarded = [
        'id', 'updated_at', 'created_at'
    ];
    use HasFactory;

    public function userProfileData()
    {
        $this->hasMany(UserProfileData::class, 'highest_education_level');
    }
}
