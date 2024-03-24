<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SelectedContractField extends Pivot
{
    use HasFactory;

    protected $table = 'selected_contract_fields';

    public $incrementing = true;

    public function customerModel()
    {
        return $this->belongsTo(Company::class, 'customer');
    }

    public function invoiceModel()
    {
        return $this->belongsTo(Invoice::class, 'invoice');
    }

    public function offerModel()
    {
        return $this->belongsTo(SaleOffer::class, 'offer');
    }

    public function offerConfirmationModel()
    {
        return $this->belongsTo(SaleOffer::class, 'offer_confirmation');
    }

    public function performanceRecordModel()
    {
        return $this->belongsTo(PerformanceRecord::class, 'performance_record');
    }

    public function contractFieldProducts()
    {
        return $this->hasMany(ContractFieldProduct::class, 'contract_field_id');
    }

    public function contractField()
    {
        return $this->belongsTo(ContractField::class, 'contract_field_id');
    }

    public function contractAttachment()
    {
        return $this->belongsTo(ContractAttachment::class, 'contract_attachment_id');
    }
}
