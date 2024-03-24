<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleHistory extends Model
{
    use HasFactory;

    protected $fillable = ['moduleable_type', 'moduleable_id', 'user_id', 'module_action', 'request_ip', 'old_fields_data', 'new_fields_data'];

    protected $casts = ['old_fields_data' => 'array', 'new_fields_data' => 'array'];

    public function moduleable()
    {
        return $this->morphTo();
    }

    public function userProfileData()
    {
        return $this->belongsTo(UserProfileData::class, 'user_id', 'user_id');
    }
}
