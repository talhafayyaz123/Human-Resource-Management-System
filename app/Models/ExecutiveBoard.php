<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutiveBoard extends Model
{
    use HasFactory;

    protected $table = "executive_board";

    protected $fillable = ["user_id"];

    public function user()
    {
        return $this->belongsTo(UserProfileData::class, "user_id");
    }
}
