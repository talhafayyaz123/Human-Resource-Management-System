<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTemplate extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'invoice_template_products', 'invoice_template_id', 'product_id')->withPivot('id', 'sale_price', 'discount', 'total_price', 'quantity', 'tax', 'price_without_tax', 'maintenance_rate', 'hourly_rate', 'service_hours', 'payment_period', 'price_per_period', 'license_id', 'duration', 'additional_description', 'parent_invoice_template_product_id','product_name')
            ->using(InvoiceTemplateProduct::class);
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
            $query->orWhereHas('company', function ($query) use ($search) {
                $query->where('company_name', 'like', '%' . $search . '%');
            });
            $query->orWhereHas('paymentPeriod', function ($query) use ($search) {
                $query->where('billing_cycle', 'like', '%' . $search . '%');
            });
            $query->orWhere('invoice_category', 'like', '%' . $search . '%');
            $query->orWhere('total_amount_without_tax', 'like', '%' . $search . '%');
        });
    }

    public function termsOfPayment()
    {
        return $this->belongsTo(TermsOfPayment::class, 'terms_of_payment');
    }

    public function invoiceProductGroupByCategory()
    {
        return $this->hasMany(InvoiceTemplateCategoryProduct::class, 'invoice_template_id');
    }

    public function parentProducts()
    {
        return $this->belongsToMany(Product::class, 'invoice_template_products', 'invoice_template_id', 'product_id')
            ->withPivot('id', 'sale_price', 'discount', 'total_price', 'quantity', 'tax', 'price_without_tax', 'maintenance_rate', 'hourly_rate', 'service_hours', 'payment_period', 'price_per_period', 'license_id', 'duration', 'additional_description', 'parent_invoice_template_product_id','product_name')
            ->whereNull('parent_invoice_template_product_id')
            ->using(InvoiceTemplateProduct::class);
    }

}
