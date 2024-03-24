<?php

namespace App\Models;

use App\Helpers\GenerateUuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyLocation extends BaseModel
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';


    /**
     * Get the company that this employee belongs to.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = GenerateUuidHelper::generateUUID();
        });
    }
    /**
     * Get the company that this employee belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function relatedCountry()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}