<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillGroup extends BaseModel
{
    use HasFactory;

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }
    public function groupSkills()
    {
        return $this->belongsToMany(Skill::class, 'group_of_skills', 'skill_group_id', 'skill_id');
    }

    public function skillGroupJobs()
    {
        return $this->belongsToMany(Job::class, 'job_skill_groups', 'skill_group_id', 'job_id');
    }

    public function skillGroupTeams()
    {
        return $this->belongsToMany(UserTeam::class, 'team_skill_groups', 'skill_group_id', 'user_teams_id', "id", "id");
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }
}
