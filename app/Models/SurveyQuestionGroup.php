<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestionGroup extends BaseModel
{
    use HasFactory;

    protected $fillable = ['type'];

    public function options()
    {
        return $this->hasMany(SurveyQuestionOption::class, 'survey_question_group_id');
    }

    public function configurator()
    {
        return $this->belongsTo(SurveyQuestionConfigurator::class, 'survey_question_configurator_id');
    }
}
