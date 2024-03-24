<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelExpense extends BaseModel
{
    use HasFactory;

    protected $fillable = ['is_added'];

    /**
     * Relational logic with request type
     */
    public function requestType()
    {
        return $this->belongsTo(TravelExpenseRequestType::class, 'request_type_id');
    }

    /**
     * Get the requester associated with the travel expense.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requester()
    {
        return $this->belongsTo(UserProfileData::class, 'user_id', 'user_id');
    }

    /**
     * Get the approvers associated with the travel expense.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function approvers()
    {
        return $this->belongsToMany(TravelExpenseApprover::class, 'travel_expense_approvers', 'travel_expense_id', 'approver_id');
    }

    /**
     * Get the approvers associated with the travel expense.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function travelExpenseApprovers()
    {
        return $this->hasMany(TravelExpenseApprover::class, 'travel_expense_id');
    }

    /**
     * Has many bills
     */
    public function bills()
    {
        return $this->hasMany(TravelExpenseReportBill::class);
    }

    /**
     * Has many private transportation data
     */
    public function transportations()
    {
        return $this->hasMany(TravelExpenseReportTransportation::class);
    }

    /**
     * Has many day specifications
     */
    public function daySpecifications()
    {
        return $this->hasMany(TravelExpenseReportDay::class);
    }

    //departure country

    public function departureCountry()
    {
        return $this->belongsTo(Country::class, 'departure_country_id');
    }

    //arrival country

    public function arrivalCountry()
    {
        return $this->belongsTo(Country::class, 'arrival_country_id');
    }

    /**
     * Relational logic of many to one between companies (customer) and travel expense
     */
    public function customer()
    {
        return $this->belongsTo(Company::class, 'customer_id');
    }
}
