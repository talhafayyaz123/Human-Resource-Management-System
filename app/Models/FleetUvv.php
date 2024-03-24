<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetUvv extends Model
{
    use HasFactory;

    protected $guarded = [
        'id', 'updated_at', 'created_at'
    ];

    public function fleetCar()
    {
        return $this->belongsTo(FleetCar::class, 'fleet_car_id');
    }

    public function files()
    {
        return $this->morphOne(UploadedFile::class, 'fileable');
    }
}
