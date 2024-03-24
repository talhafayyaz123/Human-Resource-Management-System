<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskBoard extends Model
{
    use HasFactory;
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public function taskBoardUsers()
    {
        return $this->hasMany(TaskBoardUser::class, 'task_board_id');
    }

    public function taskStatuses()
    {
        return $this->hasMany(TaskStatus::class, 'task_board_id');
    }
}
