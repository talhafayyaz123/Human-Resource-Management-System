<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOfferOptionalComponent extends BaseModel
{
    use HasFactory;

    protected $table = 'sale_offer_optional_components';

    protected $casts = [
        "products" => "array",
        "product_name" => "array"
    ];

    protected $fillable = [
        'product_name',   'type', 'product_id', 'quantity', 'hourly_rate', 'payment_period', 'tax', 'discount', 'maintenance_rate',
        'sale_price', 'products', 'product_category_id', 'sale_offer_id'
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

    public function slaLevelChange()
    {
        return $this->belongsTo(SlaLevelChange::class, 'sla_level_change_id');
    }
}
