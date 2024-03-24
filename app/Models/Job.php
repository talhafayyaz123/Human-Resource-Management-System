<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends BaseModel
{
    use HasFactory;

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('j_number', 'like', '%' . $search . '%');
            $query->orWhere('j_title', 'like', '%' . $search . '%');
        });
    }

    public function jobSkillGroups()
    {
        return $this->belongsToMany(SkillGroup::class,'job_skill_groups', 'job_id', 'skill_group_id');
    }

    public function jobLevel()
    {
        return $this->belongsTo(JobLevel::class,'job_level_id', 'id');
    }
    public function contracType()
    {
        return $this->belongsTo(FormOfContract::class,'form_of_contract_id', 'id');
    }
    public function formOfContract()
    {
        return $this->belongsTo(FormOfContract::class,'form_of_contract_id', 'id');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }
}
