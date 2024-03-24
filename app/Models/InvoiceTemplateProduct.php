<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class InvoiceTemplateProduct extends Pivot
{
    use HasFactory;

    protected $table = 'invoice_template_products';

    // relation with license model
    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function invoice()
    {
        return $this->belongsTo(InvoiceTemplate::class);
    }

    // returns the parent model of this ProductInvoice
    public function parent()
    {
        return $this->belongsTo(InvoiceTemplateProduct::class, 'parent_invoice_template_product_id');
    }

    // returns the children ProductInvoice
    public function children()
    {
        return $this->hasMany(InvoiceTemplateProduct::class, 'parent_invoice_template_product_id');
    }
}
