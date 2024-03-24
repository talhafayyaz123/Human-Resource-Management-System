<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerformanceRecord extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function customer()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'performance_record_id');
    }

    public function performanceRecordEntries()
    {
        return $this->hasMany(PerformanceRecordEntry::class, 'performance_record_id');
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('performance_number', 'like', '%' . $search . '%');
            $query->orWhereDate('start_date', 'like', '%' . $search . '%');
            $query->orWhereDate('end_date', 'like', '%' . $search . '%');
            $query->orWhere('total_hours', 'like', '%' . $search . '%');
            $query->orWhere('good_will_hours', 'like', '%' . $search . '%');
            $query->orWhere('status', 'like', '%' . $search . '%');
            $query->orWhereHas('company', function ($query) use ($search) {
                $query->where('company_number', 'like', '%' . $search . '%')->orWhere('company_name', 'like', '%' . $search . '%');
            });
        })->when($filters['company'] ?? null, function ($query, $company) {
            $query->where('company_id', $company);
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });
    }

    public function timeTrackerCompanies()
    {
        return $this->hasMany(TimeTrackerCompany::class, 'performance_record_id');
    }

    //relationship is on used for sorting
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }
}
