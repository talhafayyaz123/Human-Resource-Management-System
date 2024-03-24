<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'product_price_id');
    }

    public function productSoftware()
    {
        return $this->belongsTo(ProductSoftware::class, 'product_software_id');
    }

    public function partner()
    {
        return $this->belongsTo(Company::class, 'partner_id');
    }

}