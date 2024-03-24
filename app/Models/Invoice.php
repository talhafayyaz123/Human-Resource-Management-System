<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_invoice', 'invoice_id', 'product_id')->withPivot('id', 'sale_price', 'discount', 'total_price', 'quantity', 'tax', 'price_without_tax', 'maintenance_rate', 'hourly_rate', 'service_hours', 'payment_period', 'price_per_period', 'license_id', 'duration', 'additional_description', 'parent_product_invoice_id','product_name','maintenance_rate')
            ->using(ProductInvoice::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function performanceRecord()
    {
        return $this->belongsTo(PerformanceRecord::class, 'performance_record_id');
    }


    public function systems()
    {
        return $this->belongsTo(System::class, 'system_id');
    }

    public function user()
    {
        return $this->belongsTo(UserProfileData::class, 'user_id', 'user_id');
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
            $query->orWhere('invoices.invoice_category', 'like', '%' . $search . '%');
            $query->orWhere('invoices.invoice_type', 'like', '%' . $search . '%');
            $query->orWhere('invoices.status', 'like', '%' . $search . '%');
            $query->orWhereDate('invoices.due_date', 'like', '%' . $search . '%');
            $query->orWhereDate('invoices.created_at', 'like', '%' . $search . '%');
            $query->orWhere('invoices.total_amount_without_tax', 'like', '%' . $search . '%');
        })->when($filters['type'] ?? null, function ($query, $type) {
            $query->where('invoices.invoice_type', $type);
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('invoices.status', $status);
        });
    }

    public function referenceInvoice()
    {
        return $this->belongsTo(Invoice::class, 'reference_invoice');
    }


    public function termsOfPayment()
    {
        return $this->belongsTo(TermsOfPayment::class, 'terms_of_payment');
    }

    public function invoiceProductGroupByCategory()
    {
        return $this->hasMany(ProductInvoiceCategory::class, 'invoice_id');
    }


    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }

    public function invoiceReminderLevel()
    {
        return $this->belongsTo(InvoiceReminderLevel::class, 'reminder_level_id');
    }
}