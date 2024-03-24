<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestionOption extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id', 'updated_at', 'created_at'];

    public function group()
    {
        return $this->belongsTo(SurveyQuestionGroup::class, 'survey_question_group_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'survey_question_option_products', 'option_id', 'product_id')->withPivot('quantity');
    }
}
