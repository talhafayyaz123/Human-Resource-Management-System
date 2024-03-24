<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRoutine extends BaseModel
{
    use HasFactory;

    public function version()
    {
        return $this->belongsTo(ProductVersion::class, 'product_version_id');
    }
}
