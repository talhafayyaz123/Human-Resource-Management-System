<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillMatrix extends BaseModel
{
    use HasFactory;

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function matrixSkillGroups()
    {
        return $this->belongsToMany(SkillGroup::class,'matrix_skill_groups', 'skill_matrix_id', 'skill_group_id');
    }

    public function matrixTeams()
    {
        return $this->belongsToMany(UserTeam::class, "skill_matrix_teams", "skill_matrix_id", "user_team_id");
    }

    public function matrixSkills()
    {
        return $this->belongsToMany(Skill::class,'skill_matrix_skills', 'skill_matrix_id', 'skill_id');
    }

    public function matrixTeamMembers()
    {
        return $this->belongsToMany(UserProfileData::class, "skill_matrix_team_members", "skill_matrix_id", "team_member_id");
    }
}
