<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelExpenseReportBill extends BaseModel
{
    use HasFactory;

    /**
     * Belongs to travel expense
     */
    protected $fillable = [
        'invoice_type', 'location', 'from_date', 'to_date', 'description', 'attachment', 'travel_expense_id',
        'invoice_type_id'
    ];

    public function travelExpense()
    {
        return $this->belongsTo(TravelExpense::class);
    }

    /**
     * Belongs to travel expense invoice type
     */
    public function invoiceType()
    {
        return $this->belongsTo(TravelExpenseInvoiceType::class);
    }

    public function attachments()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

}
