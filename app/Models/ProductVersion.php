<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVersion extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function files()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

    public function dependencies()
    {
        return $this->belongsToMany(Product::class, 'product_dependencies', 'product_version_id', 'dependent_version_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_dependencies', 'dependent_version_id', 'product_version_id');
    }

    public function childrens()
    {
        return $this->belongsToMany(Product::class, 'product_childrens', 'product_version_id', 'child_version_id');
    }

    public function productSoftwareServiceChildrens()
    {
        return $this->belongsToMany(Product::class, 'product_software_service_childrens', 'product_version_id', 'product_id');
    }

    public function parents()
    {
        return $this->belongsToMany(Product::class, 'product_childrens', 'child_version_id', 'product_version_id');
    }

    public function productParameters()
    {
        return $this->hasMany(ProductParameter::class, 'product_version_id');
    }

    public function routine()
    {
        return $this->hasOne(ProductRoutine::class, 'product_version_id');
    }

    public function productInstallations()
    {
        return $this->hasMany(ProductInstallation::class, 'product_version_id');
    }

    public function productConfigurations()
    {
        return $this->hasMany(ProductConfiguration::class, 'product_version_id');
    }
}
