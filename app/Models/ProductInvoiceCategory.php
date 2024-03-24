<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInvoiceCategory extends BaseModel
{
    protected $table = 'product_invoice_categories';

    protected $fillable = ['invoice_id', 'product_ids', 'category_id', 'hourly_rate', 'discount', 'tax',
        'tax_rate', 'netto_total', 'quantity', 'additional_description','product_names'];

    protected $casts = ['product_ids' => 'array','product_names' => 'array'];

    public function category(){
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
