<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HashTag extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name'];

    public function feeds()
    {
        return $this->morphedByMany(MyFeed::class, 'morphable', 'my_feed_tags');
    }
}
