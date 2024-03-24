<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelExpenseInvoiceType extends BaseModel
{
    use HasFactory;

    /**
     * Relationship with travel expense bill
     */
    public function bills()
    {
        return $this->hasMany(TravelExpenseReportBill::class);
    }
}
