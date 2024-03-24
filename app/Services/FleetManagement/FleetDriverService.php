<?php

namespace App\Services\FleetManagement;

use Illuminate\Support\Arr;
use App\Models\FleetDriver;



class FleetDriverService
{
    /**
     * Create a new fleet car.
     *
     * @param  array  $data
     * @return \App\Models\FleetDriver
     */
    public function createFleetDriver(array $data)
    {
        $driver_data = Arr::except($data, 'user_id');
        return FleetDriver::firstOrCreate(['user_id' => $data['user_id']], $driver_data);
    }

    /**
     * Update the specified fleet car.
     *
     * @param  \App\Models\FleetDriver  $fleetDriver
     * @param  array  $data
     * @return \App\Models\FleetDriver
     */
    public function updateFleetDriver(FleetDriver $fleetDriver, array $data)
    {
        $fleetDriver->update($data);
        return $fleetDriver;
    }

    /**
     * Delete the specified fleet car.
     *
     * @param  \App\Models\FleetDriver  $fleetDriver
     * @return void
     */
    public function deleteFleetDriver(FleetDriver $fleetDriver)
    {
        $fleetDriver->delete();
    }
}
