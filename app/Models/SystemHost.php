<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemHost extends BaseModel
{
    use HasFactory;
    public function scopeFilter($query, array $filters)
    {
        return $query->when(
            $filters['search'] ?? null,
            function ($query, $search) {
                $query->where('username', 'like', '%' . $search . '%');
            }
        );
    }
}
