<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillLevel extends BaseModel
{
    use HasFactory;

    protected $table = 'skill_levels';

    protected $fillable = ['name', 'number'];

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
            $query->orWhere('number', 'like', '%' . $search . '%');
        });
    }
}
