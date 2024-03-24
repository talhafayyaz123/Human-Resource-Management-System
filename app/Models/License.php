<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['sale_offer_component_id', 'company_id', 'product_id', 'quantity', 'sale_price', 'maintenance_price', 'product_name'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function saleOfferComponent()
    {
        return $this->belongsTo(SaleOfferComponent::class);
    }

    public function productInvoice()
    {
        return $this->hasOne(ProductInvoice::class);
    }
}
