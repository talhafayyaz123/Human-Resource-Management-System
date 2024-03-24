<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyTask extends Model
{
    use HasFactory;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public function contentType()
    {
        return $this->hasMany(TaskContentType::class, 'my_task_id');
    }

    public function assignees()
    {
        return $this->hasMany(TaskAssignee::class, 'my_task_id');
    }

    public function taskStatus()
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id');
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class, 'task_id');
    }

    public function files()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }
}
