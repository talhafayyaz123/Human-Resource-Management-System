<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignee extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'my_task_id'];

    public function task()
    {
        return $this->belongsTo(MyTask::class, 'my_task_id');
    }
}
