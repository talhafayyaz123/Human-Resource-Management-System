<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductInvoice extends Pivot
{
    use HasFactory;

    protected $casts = [
        "products" => "array"
    ];

    // relation with license model
    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // returns the parent model of this ProductInvoice
    public function parent()
    {
        return $this->belongsTo(ProductInvoice::class, 'parent_product_invoice_id');
    }

    // returns the children ProductInvoice 
    public function children()
    {
        return $this->hasMany(ProductInvoice::class, 'parent_product_invoice_id');
    }
}
