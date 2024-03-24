<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestionConfigurator extends BaseModel
{
    use HasFactory;

    protected $fillable = ['type'];

    public function question()
    {
        return $this->belongsTo(Question::class, 'survey_question_id');
    }

    public function groups()
    {
        return $this->hasMany(SurveyQuestionGroup::class, 'survey_question_configurator_id');
    }

    public function conditions()
    {
        return $this->hasMany(SurveyQuestionCondition::class, 'survey_question_configurator_id');
    }
}
