<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyFeedSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'module_id', 'module_type'];
}
