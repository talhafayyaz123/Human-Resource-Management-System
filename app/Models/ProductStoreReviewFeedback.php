<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStoreReviewFeedback extends BaseModel
{
    use HasFactory;

    protected $table = 'product_store_review_feedbacks';

    protected $fillable = ['review_id', 'user_id', 'is_helpful'];
}
