<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModifyMyData extends BaseModel
{
    use HasFactory;


    protected $casts = ['change_request_data' => 'array'];

    public function userProfileData()
    {
        return $this->belongsTo(UserProfileData::class, 'user_id', 'user_id');
    }

    public function manager()
    {
        return $this->hasMany(ModifyMyDataManager::class, 'modify_my_data_id');
    }

    public function changedManager()
    {
        return $this->hasOne(ModifyMyDataManager::class, 'modify_my_data_id')
            ->where('changed_by', 1);
    }

    public function taskable()
    {
        return $this->morphMany(Task::class, 'taskable');
    }
}
