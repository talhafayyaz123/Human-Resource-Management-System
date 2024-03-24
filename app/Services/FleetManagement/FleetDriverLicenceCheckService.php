<?php

namespace App\Services\FleetManagement;

use App\Models\FleetDriver;
use App\Models\FleetDriverLicenceCheck;

class FleetDriverLicenceCheckService
{
    /**
     * Creates a new driver licence check.
     *
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function createDriverLicenceCheck(array $data): FleetDriverLicenceCheck
    {
        $fleet_driver_licence_check = FleetDriverLicenceCheck::create($data);
        $fleet_driver = FleetDriver::findOrFail($data['fleet_driver_id']);
        $fleet_driver->vehicleClasses()->sync(collect($data['vehicle_classes'])->pluck('id'));
        return $fleet_driver_licence_check;
    }
}
