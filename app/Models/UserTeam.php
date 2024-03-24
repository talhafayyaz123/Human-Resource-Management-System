<?php

namespace App\Models;

use App\Helpers\GenerateUuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTeam extends BaseModel
{
    use HasFactory;

    protected $fillable = ['id', 'department_id'];
    public $incrementing = false;
    protected $keyType = 'string';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = GenerateUuidHelper::generateUUID();
        });
    }

    /**
     * Get the team lead.
     */
    public function teamLead()
    {
        return $this->belongsTo(UserProfileData::class, "team_lead_id", 'user_id');
    }

    /**
     * Get the team members.
     */
    public function teamMembers()
    {
        return $this->belongsToMany(UserProfileData::class, "user_team_members", "user_team_id", "team_member_id", "id", "user_id");
    }

    /**
     * Get the team department.
     */
    public function department()
    {
        return $this->belongsTo(UserDepartment::class);
    }

    public function teamSkillGroups()
    {
        return $this->belongsToMany(SkillGroup::class, 'team_skill_groups', 'user_teams_id', 'skill_group_id', 'id', 'id');
    }

    public function consultingTeam()
    {
        return $this->hasOne(ConsultingTeam::class, 'team_id');
    }

    public function pmDashboardTeam()
    {
        return $this->hasOne(PMDashboardTeam::class, 'team_id');
    }


    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }
}
