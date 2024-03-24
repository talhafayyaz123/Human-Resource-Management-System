<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MyFeed extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'text', 'is_vote', 'moduleable_type', 'moduleable_id', 'old_fields_data', 'new_fields_data', 'poll_finished', 'poll_question', 'poll_date', 'feed_path'
    ];
    protected $casts = ['old_fields_data' => 'array', 'new_fields_data' => 'array'];

    public function comments()
    {
        return $this->hasMany(MyFeedComment::class, 'my_feed_id')->orderBy('created_at', 'DESC');
    }

    public function images()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

    public function voteAnswers()
    {
        return $this->hasMany(MyFeedVoteQuestion::class, 'my_feed_id');
    }

    public function moduleable()
    {
        return $this->morphTo();
    }

    public function userProfileData()
    {
        return $this->belongsTo(UserProfileData::class, 'user_id', 'user_id');
    }

    public function tags()
    {
        return $this->morphToMany(HashTag::class, 'morphable', 'my_feed_tags');
    }

    public function mentions()
    {
        return $this->morphMany(MyFeedMention::class, 'morphable');
    }
}
