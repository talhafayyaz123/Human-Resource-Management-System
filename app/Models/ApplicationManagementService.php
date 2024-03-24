<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationManagementService extends BaseModel
{
    use HasFactory;

    protected $fillable = ['ams_number', 'customer_id', 'service_hours_year', 'remaining_hours', 'hourly_rate', 'monthly_amount', 'attachment_id', 'software_id'];

    public function customer()
    {
        return $this->belongsTo(Company::class, 'customer_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'customer_id');
    }

    public function contractAttachment()
    {
        return $this->belongsTo(ContractAttachment::class, 'attachment_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'ams_id');
    }

    public function software()
    {
        return $this->belongsTo(ProductSoftware::class, 'software_id');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }

    public function timeTrackerCompanys()
    {
        return $this->morphMany(TimeTrackerCompany::class, 'module');
    }
}
