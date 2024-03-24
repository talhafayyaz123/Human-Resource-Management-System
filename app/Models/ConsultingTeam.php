<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultingTeam extends Model
{
    use HasFactory;

    protected $fillable = ['team_id'];

    // relation with user_teams
    public function team()
    {
        return $this->belongsTo(UserTeam::class, 'team_id');
    }
}
