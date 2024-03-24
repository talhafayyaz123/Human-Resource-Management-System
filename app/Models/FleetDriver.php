<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetDriver extends BaseModel
{
    use HasFactory;
    protected $guarded = [
        'id', 'updated_at', 'created_at'
    ];

    public function fleetCar()
    {
        return $this->belongsToMany(FleetCar::class, 'fleet_car_drivers')->wherePivotNull('deleted_at');
    }

    public function previouslyOwnedCars()
    {
        return $this->belongsToMany(FleetCar::class, 'fleet_car_drivers')->wherePivotNotNull('deleted_at');
    }

    public function vehicleClasses()
    {
        return $this->belongsToMany(VehicleClass::class, 'vehicle_class_fleet_driver');
    }

    public function driverLicenceChecks()
    {
        return $this->hasMany(FleetDriverLicenceCheck::class, 'fleet_driver_id');
    }

    public function taskable()
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }
}
