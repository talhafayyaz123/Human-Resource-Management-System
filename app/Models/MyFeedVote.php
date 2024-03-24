<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyFeedVote extends Model
{
    use HasFactory;

    protected $fillable = ['my_feed_id', 'user_id', 'answer_id'];
}
