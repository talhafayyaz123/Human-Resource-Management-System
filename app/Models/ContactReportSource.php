<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactReportSource extends BaseModel
{
    use HasFactory;

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function contactReports()
    {
        return $this->belongsToMany(ContactReport::class, 'contact_report_source_pivot');
    }

    // relation with companies
    public function leads()
    {
        return $this->belongsToMany(Company::class, 'lead_contact_report_source', 'contact_report_source_id', 'lead_id');
    }
}
