<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelExpenseReportDay extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'is_active', 'date', 'breakfast', 'lunch', 'dinner', 'from_time', 'to_time', 'travel_expense_id', 'expense_rate'
    ];
    /**
     * Belongs to travel expense
     */
    public function travelExpense()
    {
        return $this->belongsTo(TravelExpense::class);
    }
}
