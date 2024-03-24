<?php

namespace App\Services;

use App\Models\ProjectProtocol;
use Carbon\Carbon;

class ProjectProtocolService
{
    /**
     * Create a new fleet car.
     *
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function createProjectProtocol(array $data)
    {
        $project_protocol = ProjectProtocol::create([
            ...$data,
            "date" => Carbon::parse($data["date"])
        ]);
        return $project_protocol;
    }

    /**
     * Update the specified fleet car.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function updateProjectProtocol(ProjectProtocol $project_protocol, array $data)
    {
        $project_protocol->update([
            ...$data,
            "date" => Carbon::parse($data["date"])
        ]);
        return $project_protocol;
    }

    /**
     * Delete the specified fleet car.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @return void
     */
    public function deleteProjectProtocol(ProjectProtocol $project_protocol)
    {
        $project_protocol->delete();
    }
}
