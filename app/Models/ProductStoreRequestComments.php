<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStoreRequestComments extends Model
{
    use HasFactory;

    public function product_store()
    {
        return $this->belongsTo(ProductStoreRequest::class);
    }

    public function files()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

    public function company()
    {
        return $this->morphOne(TimeTrackerCompany::class, 'module');
    }
    
}
