<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelExpenseApprover extends BaseModel
{
    protected $table = 'travel_expense_approvers';
    public $timestamps = false;

    protected $fillable = ['travel_expense_id', 'approver_id', 'status', 'level'];

    public function travelExpense()
    {
        return $this->belongsTo(TravelExpense::class, 'travel_expense_id');
    }

    public function approver()
    {
        return $this->belongsTo(UserProfileData::class, 'approver_id', 'user_id');
    }
}
