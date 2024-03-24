<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStoreRequest extends Model
{
    use HasFactory;

    protected $fillable=['creation_date','item_number','title','description','short_description','sell_price','author_id','status','approver_notes', 'discount',
     'partner_price', 'is_show_on_admin','partner_id', 'product_id', 'service_hours', 'delimitation', 'service_description'];

    public function images()
    {
        return $this->morphMany(UploadedFile::class, 'fileable')->where('is_script', 0)->where('is_documented', 0);
    }

    public function scripts()
    {
        return $this->morphMany(UploadedFile::class, 'fileable')->where('is_script', 1)->where('is_documented', 0);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function partner()
    {
        return $this->belongsTo(Company::class, 'partner_id');
    }

    public function documentedImages()
    {
        return $this->morphMany(UploadedFile::class, 'fileable')->where('is_documented', 1)->where('is_script', 0);
    }

    public function comments()
    {

        return $this->hasMany(ProductStoreRequestComments::class);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('short_description', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('approver_notes', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        });
    }
}