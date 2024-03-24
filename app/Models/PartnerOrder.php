<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerOrder extends BaseModel
{
    use HasFactory;

    public function orderProducts()
    {
        return $this->hasMany(PartnerOrderProduct::class, 'partner_order_id');
    }

    public function partner()
    {
        return $this->belongsTo(Company::class, 'partner_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'receiver_id');
    }

    public function termOfPayment()
    {
        return $this->belongsTo(TermsOfPayment::class, 'term_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereHas('partner', function ($query) use ($search){
                $query->where('company_name',  'like', '%' . $search . '%');
            });
            $query->orWhereHas('company', function ($query) use ($search){
                $query->where('company_name',  'like', '%' . $search . '%');
            });
            $query->orWhereHas('termOfPayment', function ($query) use ($search){
                $query->where('name',  'like', '%' . $search . '%');
            });
            $query->orWhere('expiry_date', 'like', '%' . $search . '%');
            $query->orWhere('status', 'like', '%' . $search . '%');
            $query->orWhere('order_number', 'like', '%' . $search . '%');
        });
    }
}
