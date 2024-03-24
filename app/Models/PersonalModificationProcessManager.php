<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalModificationProcessManager extends Model
{
    use HasFactory;

    protected $fillable = ['manager_id'];

    public function user()
    {
        return $this->belongsTo(UserProfileData::class, 'manager_id');
    }
}
