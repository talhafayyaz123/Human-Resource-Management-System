<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'reaction_time_urgent', 'reaction_time_high', 'reaction_time_low', 'factor'];

    public function saleOfferComponent(){
        return $this->hasMany(SaleOfferComponent::class, 'sla_level_id');
    }

    public function saleOfferOptionalComponent(){
        return $this->hasMany(SaleOfferOptionalComponent::class, 'sla_level_id');
    }
}
