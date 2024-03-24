<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Relationship with products
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function servicePauschalProducts()
    {
        return $this->hasMany(Product::class)->whereIn('payment_details_type', ['service', 'pauschal']);
    }

    /**
     * Relationship with product_units
     */
    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, "default_unit");
    }
}
