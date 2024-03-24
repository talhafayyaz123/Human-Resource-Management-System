<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactReport extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    /**
     * One to one relationship with company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship of report with category
     */
    public function reportCategory()
    {
        return $this->belongsTo(ReportCategory::class, 'category_id');
    }

    /**
     * Relationship of uploaded file
     */
    public function files()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

    /**
     * Relationship of contact report with relevant company employees
     * //Talked to persons
     */
    public function companyEmployees()
    {
        return $this->belongsToMany(ContactReportCompanyEmployee::class, 'contact_report_company_employees', 'report_id', 'user_id');
    }

    /**
     * Relationship of contact report with relevant Doc hero users
     * //Talked to persons
     */
    public function employees()
    {
        return $this->belongsToMany(ContactReportUser::class, 'contact_report_users', 'report_id', 'user_id');
    }

    /**
     * Relationship of contact report resource with contact report sources
     */
    public function contactReportSources()
    {
        return $this->belongsToMany(ContactReportSource::class, 'contact_report_source_pivot');
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('subject', 'like', '%' . $search . '%')->orWhere('report_number', 'like', '%' . $search . '%')->orWhereHas('company', function ($query) use ($search) {
                $query->where('company_name', 'like', '%' . $search . '%');
            });
        })->when($filters['type'] ?? null, function ($query, $type) {
            $query->where('type', $type);
        })->when($filters['company'] ?? null, function ($query, $company) {
            $query->where('company_id', $company);
        })->when($filters['categoryId'] ?? null, function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        })->when($filters['contactSources'] ?? null, function ($query, $contactSourceId) {
            $query->whereHas('contactReportSources', function ($query) use ($contactSourceId) {
                // Apply the filter condition on the contact report sources
                $query->whereIn('contact_report_sources.id', $contactSourceId);
            });
        });
    }

    public function taskable()
    {
        return $this->morphOne(Task::class, 'taskable');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }
}
