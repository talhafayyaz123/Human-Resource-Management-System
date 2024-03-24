<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStore extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'item_number', 'product_title', 'long_description', 'short_description', 'sell_price', 'is_visible_for_partner',
        'is_visible_for_customer', 'author_id'
    ];

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('item_number', 'like', '%' . $search . '%');
            $query->orWhere('product_title', 'like', '%' . $search . '%');
        });
    }


    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_store_products', 'product_store_id', 'product_id');
    }

    public function images()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

    public function partner()
    {
        return $this->belongsTo(Company::class, 'author_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductStoreReview::class, 'product_store_id');
    }
}
