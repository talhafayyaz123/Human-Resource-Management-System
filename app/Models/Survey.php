<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\GenerateUuidHelper;

class Survey extends BaseModel
{
    use HasFactory;
    protected $fillable = ['id'];
    public $incrementing = false;
    protected $keyType = 'string';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = GenerateUuidHelper::generateUUID();
        });
    }

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class, 'survey_id');
    }

    public function chapters()
    {
        return $this->hasMany(SurveyChapter::class, 'survey_id');
    }

    public function configurations()
    {
        return $this->hasMany(SurveyConfiguration::class, 'survey_id');
    }

    public function surveyStyleConfigurations()
    {
        return $this->hasOne(SurveyStyleConfigurations::class, 'survey_id');
    }


    //not needed rn
    public function globalConfigurationProducts()
    {
        return $this->belongsToMany(Product::class, 'survey_global_configurations_products', 'survey_id', 'product_id')->withPivot('quantity');
    }
}
