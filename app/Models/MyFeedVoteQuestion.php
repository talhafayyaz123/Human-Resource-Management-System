<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyFeedVoteQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['my_feed_id', 'text'];
    public function answerVotes()
    {
        return $this->hasMany(MyFeedVote::class, 'answer_id');
    }
}
