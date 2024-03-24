<?php

namespace App\Models;

use App\Helpers\GenerateUuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDepartment extends BaseModel
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
     * Get the department location.
     */
    public function location()
    {
        return $this->belongsTo(UserLocation::class);
    }

    /**
     * Get the department location.
     */
    public function teams()
    {
        return $this->hasMany(UserTeam::class, "department_id");
    }

    /**
     * Get the department head.
     */
    public function departmentHead()
    {
        return $this->belongsTo(UserProfileData::class, "department_head_id", "user_id");
    }

    public function employees()
    {
        return $this->hasMany(UserProfileData::class, "user_department_id");
    }

    public function childDepartments()
    {
        return $this->belongsToMany(
            UserDepartment::class,
            'department_parent_child',
            'parent_department_id',
            'child_department_id'
        );
    }

    public function parentDepartments()
    {
        return $this->belongsToMany(
            UserDepartment::class,
            'department_parent_child',
            'child_department_id',
            'parent_department_id'
        );
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }
}
