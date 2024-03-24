<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaLevelIncident extends Model
{
    use HasFactory;
    protected $table = 'sla_level_incidents';

    protected $fillable = ['priority', 'incident'];

    public function saleOfferComponent(){
        return $this->hasMany(SaleOfferComponent::class, 'sla_level_incident_id');
    }

    public function saleOfferOptionalComponent(){
        return $this->hasMany(SaleOfferOptionalComponent::class, 'sla_level_incident_id');
    }

}
