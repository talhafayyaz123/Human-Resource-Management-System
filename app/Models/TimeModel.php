<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeModel extends Model
{
    use HasFactory;

    protected $fillable = ['personal_request_id', 'day', 'working_hours'];

    public function personalRequest()
    {
        return $this->belongsTo(PersonalRequest::class, 'personal_request_id');
    }
}
