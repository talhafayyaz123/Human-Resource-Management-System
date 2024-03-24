<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBankDetail extends BaseModel
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    use HasFactory;

    /**
     * Relationship with terms of payment
     */
    public function termsOfPayment()
    {
        return $this->belongsTo(TermsOfPayment::class, 'terms_of_payment');
    }
}
