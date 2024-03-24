<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskBoardUser extends Model
{
    use HasFactory;
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public function taskBoard()
    {
        return $this->belongsTo(TaskBoard::class, 'task_board_id');
    }
}
