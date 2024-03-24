<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductParameter extends BaseModel
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
