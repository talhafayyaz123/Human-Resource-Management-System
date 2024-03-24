<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobLevel extends BaseModel
{
    use HasFactory;

    protected $fillable = ['level_name', 'experience_start', 'experience_end', 'target_salary', 'limit_salary'];

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('level_name', 'like', '%' . $search . '%');
            $query->orWhere('experience_start', 'like', '%' . $search . '%');
            $query->orWhere('experience_end', 'like', '%' . $search . '%');
            $query->orWhere('target_salary', 'like', '%' . $search . '%');
            $query->orWhere('limit_salary', 'like', '%' . $search . '%');
        });
    }
}
