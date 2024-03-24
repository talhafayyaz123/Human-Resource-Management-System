<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopTemplate extends BaseModel
{
    use HasFactory;

    protected $fillable = ['template_name', 'template_version', 'status', 'consultant_id'];

    public function consultant()
    {
        return $this->belongsTo(UserProfileData::class);
    }

    public function cards()
    {
        return $this->hasMany(WorkshopTemplateCard::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'workshop_template_product', 'workshop_template_id', 'product_id');
    }

    public function files()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }
}
