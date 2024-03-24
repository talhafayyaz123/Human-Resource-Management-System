<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStoreReview extends BaseModel
{
    use HasFactory;

    protected $fillable = ['product_store_id', 'user_id', 'title', 'review', 'ratting'];

    public function images()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

    public function productStore()
    {
        return $this->belongsTo(ProductStore::class, 'product_store_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'user_id');
    }

    public function helpfulFeedback()
    {
        return $this->hasMany(ProductStoreReviewFeedback::class, 'review_id')
            ->where('is_helpful', 1);
    }

    public function badFeedback()
    {
        return $this->hasMany(ProductStoreReviewFeedback::class, 'review_id')
            ->where('is_helpful', 0);
    }

    public function reports()
    {
        return $this->belongsToMany(ReviewReport::class, 'product_store_review_reports', 'review_id', 'review_report_id')
            ->withPivot(['user_id']);
    }

    public function userInformation()
    {
        return $this->hasOne(UserInformation::class, 'user_id', 'user_id');
    }
}
