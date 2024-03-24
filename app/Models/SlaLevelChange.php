<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaLevelChange extends Model
{
    use HasFactory;
    protected $table = 'sla_level_changes';

    protected $fillable = ['priority', 'change'];

    public function saleOfferComponent(){
        return $this->hasMany(SaleOfferComponent::class, 'sla_level_change_id');
    }

    public function saleOfferOptionalComponent(){
        return $this->hasMany(SaleOfferOptionalComponent::class, 'sla_level_change_id');
    }

}
