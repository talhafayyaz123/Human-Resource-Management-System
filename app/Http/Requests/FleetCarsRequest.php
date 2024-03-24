<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FleetCarsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'licenceNumber' => 'required|string',
            'vim' => 'required|string',
            'brand' => 'required|string',
            'model' => 'required|string',
            'fuelType' => 'required|in:diesel,electronic,gasoline',
            'leasingRate' => 'required|numeric',
            'leasingStartDate' => 'required|date|before:2038-01-19',
            'leasingEndDate' => 'required|date|before:2038-01-19',
            'miles' => 'required|numeric',
            'totalMileage' => 'required|numeric',
            'status' => 'required|in:ready,out_of_service',
            'driverId' => 'nullable|string',
            'carType' => 'required',
            'assetNumber' => 'nullable|string',
            'color' => 'required|string',
            'kilowatt' => 'required|string',
            'modelKey' => 'required|string',
            'modelDetail' => 'required|string',
            'extraEquipment' => 'required',
            'contactNumber' => 'required|string',
            'deliveryDate' => 'required|date|before:2038-01-19',
            'contractPeriodMonths' => 'required|integer',
            'leasing.perAdditionalKilometers' => 'required|numeric',
            'leasing.freeExtraKilometers' => 'required|numeric',
            'leasing.perLessKilometers' => 'required|numeric',
            'leasing.freeLessKilometers' => 'required|numeric',
            'damageProtection.perAdditionalKilometers' => 'required|numeric',
            'damageProtection.freeExtraKilometers' => 'required|numeric',
            'damageProtection.perLessKilometers' => 'required|numeric',
            'damageProtection.freeLessKilometers' => 'required|numeric',
            'maintenanceAndAbstraction.perAdditionalKilometers' => 'required|numeric',
            'maintenanceAndAbstraction.freeExtraKilometers' => 'required|numeric',
            'maintenanceAndAbstraction.perLessKilometers' => 'required|numeric',
            'maintenanceAndAbstraction.freeLessKilometers' => 'required|numeric',
            'updatedAt' => 'nullable|date|before:2038-01-19'
        ];
    }
}
