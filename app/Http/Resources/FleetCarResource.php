<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FleetCarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'licenceNumber' => $this->licence_number,
            'vim' => $this->vim,
            'brand' => $this->brand,
            'model' => $this->model,
            'fuelType' => $this->fuel_type,
            'leasingRate' => $this->leasing_rate,
            'leasingStartDate' => Carbon::parse($this->leasing_start_date)->toDateString(),
            'leasingEndDate' => Carbon::parse($this->leasing_end_date)->toDateString(),
            'miles' => $this->miles,
            'totalMileage' => $this->total_mileage,
            'status' => $this->status,
            'driverId' => $this->fleetDriver?->last()?->user_id ?? null,
            'leasing' => [
                "perAdditionalKilometers" => $this->leasing["perAdditionalKilometers"] ?? null,
                "freeExtraKilometers" => $this->leasing["freeExtraKilometers"] ?? null,
                "perLessKilometers" => $this->leasing['perLessKilometers'] ?? null,
                "freeLessKilometers" => $this->leasing['freeLessKilometers'] ?? null
            ],
            'damageProtection' => [
                "perAdditionalKilometers" => $this->damage_protection['perAdditionalKilometers'] ?? null,
                "freeExtraKilometers" => $this->damage_protection['freeExtraKilometers'] ?? null,
                "perLessKilometers" => $this->damage_protection['perLessKilometers'] ?? null,
                "freeLessKilometers" => $this->damage_protection['freeLessKilometers'] ?? null
            ],
            'maintenanceAndAbstraction' => [
                "perAdditionalKilometers" => $this->maintenance_and_abstraction['perAdditionalKilometers'] ?? null,
                "freeExtraKilometers" => $this->maintenance_and_abstraction['freeExtraKilometers'] ?? null,
                "perLessKilometers" => $this->maintenance_and_abstraction['perLessKilometers'] ?? null,
                "freeLessKilometers" => $this->maintenance_and_abstraction['freeLessKilometers'] ?? null
            ],
            'color' => $this->color,
            'kilowatt' => $this->kilowatt,
            'modelKey' => $this->model_key,
            'modelDetail' => $this->model_detail,
            'extraEquipment' => $this->extra_equipment,
            'contactNumber' => $this->contact_number,
            'deliveryDate' => Carbon::parse($this->delivery_date)->toDateString(),
            'contractPeriodMonths' => $this->contract_period_months,
            'carType' => $this->car_type,
            'assetNumber' => $this->asset_number,
            'updatedAt' => Carbon::parse($this->updated_at)->toDateString(),
            'files' => $this->files->map(function ($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->viewable_name,
                    'size' => $file->storage_size,
                    'storageName' => $file->storage_name,
                ];
            }) ?? [],
            'uvv' => [
                'date' => "",
                'managerId' => "",
                'nextUVV' => "",
                'files' => [],
            ],
            'uvvRecords' => $this->Uvvs->map(function ($uvv) {
                return [
                    'id' => $uvv->id,
                    'date' => isset($uvv->date) ? Carbon::parse($uvv->date)->toDateString() : '',
                    'nextDate' => isset($uvv->next_date) ? Carbon::parse($uvv->next_date)->toDateString() : '',
                    'managerId' => $uvv->manager_id,
                    'documents' => $uvv?->files
                ];
            })
        ];
    }
}
