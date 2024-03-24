<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestionCondition extends BaseModel
{
    use HasFactory;

    public function configurator()
    {
        return $this->belongsTo(SurveyQuestionConfigurator::class, 'survey_question_configurator_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'survey_question_conditional_products', 'condition_id', 'product_id')->withPivot('quantity');
    }

    public function conditionOptions()
    {
        return $this->hasMany(SurveyQuestionConditionOption::class, 'condition_id');
    }
}
