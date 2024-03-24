<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewReport extends BaseModel
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('title', 'like', '%' . $search . '%');
            $query->orWhere('description', 'like', '%' . $search . '%');
        });
    }
}
