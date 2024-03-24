<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'task_hours' => 'array',
        'relationships' => 'array'
    ];

    /**
     * Get the milestone.
     */
    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }
}
