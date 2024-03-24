<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_required', 'level'];

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
            $query->orWhere('level', 'like', '%' . $search . '%');
        });
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }

    public function skillLevel()
    {
        return $this->belongsTo(SkillLevel::class, 'skill_level_id');
    }
}
