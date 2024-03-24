<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeInterview extends Model
{
    use HasFactory;

    protected $guarded = [
        'id', 'created_at'
    ];
    protected $casts = [
        'personal_skills' => 'array',
        'company_values' => 'array',
        'social_competence' => 'array',
        'professional_technical_abilities' => 'array',
        'team_personal_skills' => 'array',
        'team_company_values' => 'array',
        'team_social_competence' => 'array',
        'team_professional_technical_abilities' => 'array',
        'final_personal_skills' => 'array',
        'final_company_values' => 'array',
        'final_social_competence' => 'array',
        'final_professional_technical_abilities' => 'array',
        'overall_satisfaction' => 'array',
        'satisfaction_with_superiors' => 'array'
    ];

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('employee_name', 'like', '%' . $search . '%');
            $query->orWhere('created_at', 'like', '%' . $search . '%');
            $query->orWhereHas('creator', function ($query) use ($search) {
                $query->where('first_name', 'like', '%' . $search . '%');
                $query->orWhere('last_name', 'like', '%' . $search . '%');
            });
        });
    }

    public function creator()
    {
        return $this->belongsTo(UserProfileData::class, 'creator_id', 'user_id');
    }
}
