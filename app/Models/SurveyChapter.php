<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyChapter extends BaseModel
{
    use HasFactory;

    /**
     * One to many relationship with questions
     */
    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class, 'survey_chapter_id');
    }
}
