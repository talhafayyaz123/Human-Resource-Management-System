<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
            $query->orWhere('project_number', 'like', '%' . $search . '%');
            $query->orWhereHas('company', function ($query) use ($search) {
                $query->where('company_name', 'LIKE', '%' . $search . '%');
            });
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });
    }

    /**
     * Get the company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the milestones.
     */
    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function projectProtocols()
    {
        return $this->hasMany(ProjectProtocol::class, 'project_id');
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(UserProfileData::class, 'project_assigned_users', 'project_id', 'user_profile_id')->withTimestamps();
    }

    public function orderConfirmations()
    {
        return $this->belongsToMany(SaleOffer::class, 'project_order_confirmations', 'project_id', 'order_confirmation_id');
    }

}
