<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends BaseModel
{
    use HasFactory;
    protected $fillable = ['key'];
}
