<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamSkillGroup extends Model
{
    protected $fillable = ['id', 'skill_group_id', "user_teams_id"];
    use HasFactory;
}
