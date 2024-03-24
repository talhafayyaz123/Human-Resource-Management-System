<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends BaseModel
{
    use HasFactory;

    public function chapter()
    {
        return $this->belongsTo(SurveyChapter::class, 'survey_chapter_id');
    }

    public function configurator()
    {
        return $this->hasOne(SurveyQuestionConfigurator::class, 'survey_question_id');
    }
}
