<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Milestone extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    /**
     * Get the project.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the tasks.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
