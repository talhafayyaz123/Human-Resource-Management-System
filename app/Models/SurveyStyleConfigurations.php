<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyStyleConfigurations extends BaseModel
{
    use HasFactory;
    protected $casts = [
        'steps' => 'array',
        'question_details' => 'array',
        'cart' => 'array',
        'product_details' => 'array',
        'layout' => 'array'
    ];
}
