<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOffer extends BaseModel
{
    use HasFactory;

    /**
     * Relationship with company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship with terms of payment
     */
    public function term()
    {
        return $this->belongsTo(TermsOfPayment::class);
    }
    

    /**
     * Relationship with project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relationship with components
     */
    public function components()
    {
        return $this->hasMany(SaleOfferComponent::class);
    }

    /**
     * Relationship with optional components
     */
    public function optionalComponents()
    {
        return $this->hasMany(SaleOfferOptionalComponent::class);
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }

    public function termsOfPayment()
    {
        return $this->belongsTo(TermsOfPayment::class, 'term_id');
    }
}