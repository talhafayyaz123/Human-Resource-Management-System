<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyFeedMention extends Model
{
    use HasFactory;

    protected $fillable = ['my_feed_id', 'user_id','morphable_id', 'morphable_type'];

    public function morphable()
    {
        return $this->morphTo();
    }
}