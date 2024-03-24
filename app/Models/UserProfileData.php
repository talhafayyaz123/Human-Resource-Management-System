<?php

namespace App\Models;

use App\Traits\GetUserProfilePicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfileData extends BaseModel
{
    use HasFactory, GetUserProfilePicture;
    protected $casts = [
        'children_data' => 'array',
    ];


    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    /**
     * Relationship of user profile with invoice
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'user_id');
    }

    /**
     * Relationship of user profile with job
     */
    public function jobData()
    {
        return $this->hasOne(UserJobData::class, 'user_profile_id');
    }

    public function userJobData()
    {
        return $this->hasOne(UserJobData::class, 'user_profile_id');
    }

    /**
     * Relationship of user profile with compensation
     */
    public function userCompensationData()
    {
        return $this->hasOne(UserCompensationData::class, 'user_profile_id');
    }

    /**
     * Relationship of team with user
     */
    public function teams()
    {
        return $this->belongsToMany(UserTeam::class, "user_team_members", "team_member_id", "user_team_id", "user_id", "id");
    }

    /**
     * Employee Vacations
     */
    public function employeeVacations()
    {
        return $this->hasMany(EmployeeVacation::class, 'user_id', 'user_id');
    }

    /**
     * Employee government hours
     */
    public function employeeGovernmentHours()
    {
        return $this->hasMany(TimeTrackerGovernment::class, 'user_id', 'user_id');
    }

    /**
     * Get compiled user monthly data
     */
    public function employeeTimeTrackerData()
    {
        return $this->hasMany(TimeTrackerUserData::class, 'user_id', 'user_id');
    }

    public function documentAndContract()
    {
        return $this->hasMany(DocumentAndContract::class, 'user_profile_id');
    }

    public function department()
    {
        return $this->belongsTo(UserDepartment::class, 'user_department_id');
    }

    public function highestSchoolDegree()
    {
        return $this->belongsTo(HighestSchoolDegree::class, 'highest_school_degree_id');
    }

    public function highestEducationLevel()
    {
        return $this->belongsTo(HighestEducationLevel::class, 'highest_education_level_id');
    }

    public function continuousImprovementProcesses()
    {
        return $this->belongsToMany(ContinuousImprovementProcess::class, 'continuous_improvement_process_approvers', 'approver_id', 'cip_id')->withPivot(['id', 'status', 'approver_type'])->using(ContinuousImprovementProcessApprover::class);;
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }

    public function manager()
    {
        return $this->hasOne(PersonalModificationProcessManager::class, 'manager_id');
    }

    public function personalRequestApprover()
    {
        return $this->hasOne(PersonalRequestApprover::class, 'approver_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_assigned_users', 'user_profile_id', 'project_id')->withTimestamps();
    }

    /**
     * Check if current user is a team member
     */
    public function isTeamMember()
    {
        return $this->teams->isNotEmpty();
    }

    /**
     * Relational logic of users with teams as a team lead
     */
    public function teamLead()
    {
        return $this->hasMany(UserTeam::class, 'team_lead_id', 'user_id');
    }

    /**
     * Check if current user is a team lead
     */
    public function isTeamLead()
    {
        return $this->teams->isNotEmpty() && $this->teams->where('team_lead_id', $this->user_id)->isNotEmpty();
    }

    /**
     * Check if current user is a department head
     */
    public function isDepartmentHead()
    {
        return $this->department && $this->department->departmentHead && $this->user_id === $this->department->departmentHead->user_id;
    }

    /**
     * Get supervisor of the current user
     */
    public function getImmediateSupervisor()
    {
        $current_user_email = $this->email;

        $top_level_supervisors = UserProfileData::whereIn('email', ['t.schmidt-tudl@docshero.de', 'm.rupprecht@docshero.de'])->get();

        $supervisors = []; // Initialize an array for supervisors.
        if ($current_user_email === 't.schmidt-tudl@docshero.de') {

            // Handle the case when the current user is specifically 'Tobias'.
            $supervisors[] = UserProfileData::where('email', 'm.rupprecht@docshero.de')->first(); // Supervisor is 'Marcel'.

        } elseif ($current_user_email === 'm.rupprecht@docshero.de') {

            // Handle the case when the current user is specifically 'Marcel'.
            $supervisors[] = UserProfileData::where('email', 't.schmidt-tudl@docshero.de')->first(); // Supervisor is 'Tobias'.
        } elseif ($current_user_email === 's.prechtl@docshero.de' || $current_user_email === 's.pfeil@docshero.de') {

            $supervisors =  $top_level_supervisors;
        } elseif ($this->isTeamLead()) {

            // Handle the case when the current user is a team lead.
            $supervisor = $this->teams->first()->department?->departmentHead;

            //in case when department head is not exist then supervisors are  Marcel and Tobias.
            if (!isset($supervisor) && !empty($supervisor)) {
                $supervisors = $top_level_supervisors;
            }

            if (isset($supervisor) && !empty($supervisor)) {
                if ($supervisor->user_id !== $this->user_id) {
                    $supervisors[] = $supervisor;
                }
            }
        } elseif ($this->isTeamMember()) {

            // Handle the case when the current user is a team member.
            $supervisor = $this->teams->first()->teamLead;

            //in case of team lead not exist supervisor is department head.
            if (!isset($supervisor) && empty($supervisor)) {
                $department_head = $this->teams->first()->department?->departmentHead;
            }

            //in case of department head not exist then supervisors are  Marcel and Tobias.
            if (isset($department_head) && !empty($department_head)) {
                $supervisor = $department_head;
            } else {
                $supervisors = $top_level_supervisors;
            }


            if (isset($supervisor) && !empty($supervisor)) {
                if ($supervisor->user_id !== $this->user_id) {

                    $supervisors[] = $supervisor;
                }
            }
        } elseif ($this->isDepartmentHead()) {

            $supervisors = $top_level_supervisors;
        } else {

            // if current user is null then supervisors are Marcel & Tobias.
            $supervisors = $top_level_supervisors;
        }

        // Customize the data you want to retrieve for the supervisor and add additional information.
        $supervisorData = [];
        return $supervisors;
        foreach ($supervisors as $supervisor) {
            $supervisorData[] = [
                'id' => $supervisor->id,
                'user_id' => $supervisor->user_id,
                'first_name' => $supervisor->first_name,
                'last_name' => $supervisor->last_name,
                'department' => $supervisor->department?->name,
                'profilePicUrl' => $this->getProfilePicture($supervisor->user_id),
            ];
        }

        return $supervisorData;
    }
}
