<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductRule extends BaseModel
{
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
