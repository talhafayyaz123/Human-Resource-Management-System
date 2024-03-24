<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelReceipt extends BaseModel
{
    use HasFactory;
    protected $guarded = [
        'id', 'updated_at', 'created_at'
    ];

    //relations
    public function fleetCar()
    {
        return $this->belongsTo(FleetCar::class, 'fleet_car_id');
    }
}
