<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageLocation extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('storage_location', 'like', '%' . $search . '%');
        });
    }
}
