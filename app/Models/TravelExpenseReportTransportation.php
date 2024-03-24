<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelExpenseReportTransportation extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'driven_kilometer', 'travel_expense_id', 'amount', 'car_type', 'fleet_car_id'
    ];

    /**
     * Belongs to travel expense
     */
    public function travelExpense()
    {
        return $this->belongsTo(TravelExpense::class);
    }

    public function fleetCar()
    {
        return $this->belongsTo(FleetCar::class, 'fleet_car_id');
    }
}
