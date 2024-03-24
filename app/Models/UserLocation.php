<?php

namespace App\Models;

use App\Helpers\GenerateUuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLocation extends BaseModel
{
    use HasFactory;

    protected $fillable = ['id'];
    public $incrementing = false;
    protected $keyType = 'string';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = GenerateUuidHelper::generateUUID();
        });
    }

    /**
     * Get the departments.
     */
    public function departments()
    {
        return $this->hasMany(UserDepartment::class);
    }

    public function jobData()
    {
        return $this->hasOne(UserJobData::class, 'user_location_id');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }
}
