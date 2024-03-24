<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaInfrastructure extends BaseModel
{
    use HasFactory;

    protected $table = 'sla_infrastructures';

    protected $fillable = ['name', 'is_access', 'user_included', 'additional_user_cost', 'category', 'price'];

    public function company()
    {
        return $this->hasMany(Company::class, 'sla_infrastructure');
    }

    public function saleOfferComponent()
    {
        return $this->hasMany(SaleOfferComponent::class, 'sla_infrastructure_id');
    }

    public function saleOfferOptionalComponent()
    {
        return $this->hasMany(SaleOfferOptionalComponent::class, 'sla_infrastructure_id');
    }
}
