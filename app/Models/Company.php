<?php

namespace App\Models;

use App\Helpers\GenerateUuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id'];
    public $incrementing = false;
    protected $keyType = 'string';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = GenerateUuidHelper::generateUUID();
        });
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('company_name', 'like', '%' . $search . '%');
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        })->when($filters['leadStatus'] ?? null, function ($query, $lead_status) {
            $query->where('lead_status_id', $lead_status);
        });
    }

    /**
     * Get the locations that this company has.
     */
    public function locations()
    {
        return $this->hasMany(CompanyLocation::class);
    }

    public function systems()
    {
        return $this->hasMany(System::class, 'customer_id');
    }

    /**
     * Get the bank details that this supplier has.
     */
    public function bankDetails()
    {
        return $this->hasMany(CustomerBankDetail::class, 'customer_id');
    }

    //getting the head quater of a specific location
    public function headQuarters()
    {
        return $this->hasOne(CompanyLocation::class)->where('is_head_quarters', true);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    /**
     * Relationship with terms of payment
     */
    public function termsOfPayment()
    {
        return $this->belongsTo(TermsOfPayment::class, 'terms_of_payment');
    }

    /**
     * This leads have multiple assignees.
     */
    public function assignees()
    {
        return $this->hasMany(CompanyUsers::class, 'company_id');
    }

    /**
     * a company has many invoices
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    public function timeTrackerCompany()
    {
        return $this->hasMany(TimeTrackerCompany::class);
    }

    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'lead_status_id', 'id');
    }

    public function contactReports()
    {
        return $this->hasMany(ContactReport::class, 'company_id');
    }

    public function contactReportSources()
    {
        return $this->belongsToMany(ContactReportSource::class, 'lead_contact_report_source', 'lead_id', 'contact_report_source_id');
    }

    public function slaInfrastructure()
    {
        return $this->belongsTo(SlaInfrastructure::class, 'sla_infrastructure');
    }

    public function slaLevel()
    {
        return $this->belongsTo(SlaLevel::class, 'sla_level');
    }

    public function slaServiceTime()
    {
        return $this->belongsTo(SlaServiceTime::class, 'sla_service_time');
    }

    public function projectProtocols()
    {
        return $this->hasMany(ProjectProtocol::class, 'customer_id');
    }

    public function applicationManagementServices()
    {
        return $this->hasMany(ApplicationManagementService::class, 'customer_id');
    }

    public function priceLists()
    {
        return $this->belongsToMany(PriceList::class, 'company_price_list', 'customer_id', 'price_list_id');
    }

    public function defaultPaymentPeriod()
    {
        return $this->belongsTo(PaymentPeriod::class, 'default_payment_period');
    }

    //relationship with offers
    public function saleOffers()
    {
        return $this->hasMany(SaleOffer::class, 'company_id');
    }

    public function defaultServiceProduct()
    {
        return $this->belongsTo(Product::class, 'default_service_product');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }

    public function image()
    {
        return $this->morphOne(UploadedFile::class, 'fileable');
    }

    public function partner()
    {
        return $this->belongsTo(Company::class, 'partner_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'partner_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'company_id');
    }
}