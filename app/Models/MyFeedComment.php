<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyFeedComment extends Model
{
    use HasFactory;

    protected $fillable = ['my_feed_id', 'text', 'user_id'];

    public function tags()
    {
        return $this->morphToMany(HashTag::class, 'morphable', 'my_feed_tags');
    }

    public function mentions()
    {
        return $this->morphMany(MyFeedMention::class, 'morphable');
    }

    public function image()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

    public function userProfileData()
    {
        return $this->belongsTo(UserProfileData::class, 'user_id', 'user_id');
    }
}
