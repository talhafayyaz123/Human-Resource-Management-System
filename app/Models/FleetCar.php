<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetCar extends BaseModel
{
    use HasFactory;
    protected $guarded = [
        'id', 'created_at'
    ];
    protected $casts = [
        'leasing' => 'array',
        'damage_protection' => 'array',
        'maintenance_and_abstraction' => 'array'
    ];

    public $timestamps = false;

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('brand', 'like', '%' . $search . '%')->orWhere('model', 'like', '%' . $search . '%')->orWhere('licence_number', 'like', '%' . $search . '%');
        })->when($filters['fuelType'] ?? null, function ($query, $type) {
            $query->where('fuel_type', $type);
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        })->when($filters['driverId'] ?? null, function ($query, $driverId) {
            $query->where('driver_id', $driverId);
        });
    }

    public function getFreeExtraKilometers()
    {
        return ($this->leasing['freeExtraKilometers'] ?? 0)
            + ($this->damage_protection['freeExtraKilometers'] ?? 0)
            + ($this->maintenance_and_abstraction['freeExtraKilometers'] ?? 0);
    }

    public function getFreeLessKilometers()
    {
        return (($this->leasing['freeLessKilometers'] ?? 0)
            + ($this->damage_protection['freeLessKilometers'] ?? 0)
            + ($this->maintenance_and_abstraction['freeLessKilometers'] ?? 0));
    }

    public function getFirstSum()
    {
        return (($this->leasing['perAdditionalKilometers'] ?? 0)
            + ($this->damage_protection['perAdditionalKilometers'] ?? 0)
            + ($this->maintenence_and_abstraction['perAdditionalKilometers'] ?? 0));
    }

    public function getSecondSum()
    {
        return (($this->leasing['perLessKilometers'] ?? 0)
            + ($this->damage_protection['perLessKilometers'] ?? 0)
            + ($this->maintenence_and_abstraction['perLessKilometers'] ?? 0));
    }

    //relations
    public function fuelReceipt()
    {
        return $this->hasMany(FuelReceipt::class, 'fleet_car_id');
    }

    /**
     * Relationship of uploaded file
     */
    public function files()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

    public function fleetDriver()
    {
        return $this->belongsToMany(FleetDriver::class, 'fleet_car_drivers')->wherePivotNull('deleted_at');
    }
    public function taskable()
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    public function moduleHistory()
    {
        return $this->morphMany(ModuleHistory::class, 'moduleable')->orderBy('id', 'desc');
    }

    public function Uvvs()
    {
        return $this->hasMany(FleetUvv::class, 'fleet_car_id');
    }
}
