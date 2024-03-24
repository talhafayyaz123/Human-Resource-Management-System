<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelExpenseRequestType extends BaseModel
{
    use HasFactory;

    /**
     * Has many relational logic with travel expense
     */
    public function travelExpenses()
    {
        return $this->hasMany(TravelExpense::class);
    }
}
