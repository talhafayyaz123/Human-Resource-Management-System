<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function paymentPeriod()
    {
        return $this->belongsTo(PaymentPeriod::class, 'payment_period');
    }

}
