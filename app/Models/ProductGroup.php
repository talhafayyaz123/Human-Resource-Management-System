<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGroup extends BaseModel
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

    public function softwareProducts()
    {
        return $this->hasMany(Product::class)->where('payment_details_type', 'software');
    }

    public function serviceProducts()
    {
        return $this->hasMany(Product::class)->whereIn('payment_details_type', ['service', 'pauschal']);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }
}
