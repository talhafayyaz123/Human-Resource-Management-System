<?php

namespace App\Models;

use App\Helpers\GenerateUuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierLocation extends BaseModel
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';


    /**
     * Get the supplier that this employee belongs to.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = GenerateUuidHelper::generateUUID();
        });
    }
    /**
     * Get the supplier that this employee belongs to.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function countryRelation()
    {
        return $this->belongsTo(Country::class, 'country');
    }
}
