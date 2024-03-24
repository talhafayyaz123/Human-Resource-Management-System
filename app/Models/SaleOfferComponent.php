<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOfferComponent extends BaseModel
{
    use HasFactory;

    protected $casts = [
        "products" => "array",
        "product_name" => "array"
    ];

    protected $fillable = [
       'product_name', 'type', 'product_id', 'quantity', 'hourly_rate', 'payment_period', 'tax', 'discount', 'maintenance_rate',
        'sale_price', 'products', 'product_category_id', 'sale_offer_id', 'sla_infratructure_id', 'sla_service_time_id', 'sla_level_id',
        'additional_description', 'total_netto', 'total_amount', 'price_per_period', 'duration', 'parent_component_id'
    ];

    
    /**
     * Relationship with sales offer
     */
    public function offer()
    {
        return $this->belongsTo(SaleOffer::class, 'sale_offer_id');
    }

    /**
     * Relationship with product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship with license
     */
    public function license()
    {
        return $this->hasOne(License::class);
    }

    public function slaInfrastructure()
    {
        return $this->belongsTo(SlaInfrastructure::class, 'sla_infrastructure_id');
    }

    public function slaServiceTime()
    {
        return $this->belongsTo(SlaServiceTime::class, 'sla_service_time_id');
    }

    public function slaLevel()
    {
        return $this->belongsTo(SlaLevel::class, 'sla_level_id');
    }

    // returns the parent model of this SaleOfferComponent
    public function parent()
    {
        return $this->belongsTo(SaleOfferComponent::class, 'parent_component_id');
    }

    // returns the child SaleOfferComponents 
    public function children()
    {
        return $this->hasMany(SaleOfferComponent::class, 'parent_component_id');
    }
}
