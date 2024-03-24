<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends BaseModel
{
    use HasFactory;

    /**
     * Get the company that this ticket belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get ticket comments.
     */
    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    /**
     * Get ticket user profile.
     */
    public function userProfileData()
    {
        return $this->belongsTo(UserProfileData::class, 'user_id');
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('title', 'like', '%' . $search . '%');
            $query->orWhere('ticket_number', 'like', '%' . $search . '%');
            $query->orWhereHas('company', function ($query) use ($search) {
                $query->where('company_name', 'like', '%' . $search . '%');
            });
        })->when($filters['state'] ?? null, function ($query, $state) {
            $query->where('state', $state);
        })->when($filters['priority'] ?? null, function ($query, $priority) {
            $query->where('priority', $priority);
        })->when($filters['assigneeId'] ?? null, function ($query, $assignee) {
            $query->where('assignee', $assignee);
        })->when($filters['isArchived'] ?? null, function ($query, $archived) {
            $query->where('is_archived', $archived);
        });
    }

    public function software()
    {
        return $this->belongsTo(ProductSoftware::class, 'software_id');
    }

    public function ams()
    {
        return $this->belongsTo(ApplicationManagementService::class, 'ams_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
