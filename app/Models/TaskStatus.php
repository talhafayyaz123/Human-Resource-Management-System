<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public function taskBoard()
    {
        return $this->belongsTo(TaskBoard::class, 'task_board_id');
    }

    public function myTasks()
    {
        return $this->hasMany(MyTask::class, 'task_status_id');
    }

    public function outgoingTransitions()
    {
        return $this->hasMany(TaskStatusTransition::class, 'from_status_id');
    }

    public function incomingTransitions()
    {
        return $this->hasMany(TaskStatusTransition::class, 'to_status_id');
    }
}
