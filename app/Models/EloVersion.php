<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EloVersion extends BaseModel
{
    use HasFactory;
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];
    /**
     * One to many relationship with product
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function software()
    {
        return $this->belongsTo(ProductSoftware::class, 'software_id');
    }

    public function productSoftware()
    {
        return $this->belongsTo(ProductSoftware::class, 'software_id');
    }
}
