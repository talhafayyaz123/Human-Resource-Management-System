<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditorInvoice extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['invoice_number', 'invoice_type', 'status', 'due_date', 'start_date', 'end_date', 'company_id', 'user_id', 'invoice_period', 'total_amount', 'total_amount_without_tax', 'total_tax_amount', 'terms_of_payment', 'custom_notes_field', 'draft_status_change_date', 'contact_person', 'receiver_type', 'invoice_date', 'payment_terms'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(UserProfileData::class, 'user_id');
    }

    public function paymentPeriod()
    {
        return $this->belongsTo(PaymentPeriod::class, 'invoice_period');
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('invoice_number', 'like', '%' . $search . '%');
            $query->orWhereHas('company', function ($query) use ($search) {
                $query->where('company_name', 'like', '%' . $search . '%');
            });
            $query->orWhereHas('paymentPeriod', function ($query) use ($search) {
                $query->where('billing_cycle', 'like', '%' . $search . '%');
            });
            $query->orWhere('invoice_type', 'like', '%' . $search . '%');
            $query->orWhere('status', 'like', '%' . $search . '%');
            $query->orWhereDate('due_date', 'like', '%' . $search . '%');
            $query->orWhereDate('created_at', 'like', '%' . $search . '%');
            $query->orWhere('total_amount_without_tax', 'like', '%' . $search . '%');
        })->when($filters['type'] ?? null, function ($query, $type) {
            $query->where('invoice_type', $type);
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });
    }

    public function termsOfPayment()
    {
        return $this->belongsTo(TermsOfPayment::class, 'terms_of_payment');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }

}
