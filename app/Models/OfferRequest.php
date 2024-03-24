<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferRequest extends Model
{
    use HasFactory;

    protected $fillable = ['offer_request_number', 'receiver_type', 'receiver_id', 'text', 'grouped_by', 'created_by', 'project_id', 'status', 'contact_person', 'request_status'];

    /**
     * Relationship with company
     */
    public function receiver()
    {
        return $this->belongsTo(Company::class, 'receiver_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'receiver_id');
    }

    
    /**
     * Relationship with components
     */
    public function components()
    {
        return $this->hasMany(OfferRequestComponent::class, 'offer_request_id');
    }

    public function employee()
    {
        return $this->belongsTo(UserProfileData::class, 'created_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}