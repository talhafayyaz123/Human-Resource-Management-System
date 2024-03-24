<?php

namespace App\Services;

use App\Models\ContractType;

class ContractTypeService
{
    /**
     * Create a new fleet car.
     *
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function createContractType(array $data)
    {
        $contract_type = ContractType::create($data);
        return $contract_type;
    }

    /**
     * Update the specified fleet car.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function updateContractType(ContractType $contract_type, array $data)
    {
        $contract_type->update($data);
        return $contract_type;
    }

    /**
     * Delete the specified fleet car.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @return void
     */
    public function deleteContractType(ContractType $contract_type)
    {
        $contract_type->delete();
    }
}
