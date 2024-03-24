<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaServiceTime extends BaseModel
{
    use HasFactory;

    protected $table = 'sla_service_times';

    protected $fillable = ['name', 'days', 'from', 'to', 'factor'];

    protected $casts = ['days' => 'array'];

    public function company(){
        return $this->hasMany(Company::class, 'sla_service_time');
    }

    public function saleOfferComponent(){
        return $this->hasMany(SaleOfferComponent::class, 'sla_service_time_id');
    }

    public function saleOfferOptionalComponent(){
        return $this->hasMany(SaleOfferOptionalComponent::class, 'sla_service_time_id');
    }

}
