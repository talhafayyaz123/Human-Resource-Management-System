<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskContentType extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'value', 'my_task_id', 'is_checked'];
}
