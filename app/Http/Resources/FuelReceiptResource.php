<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FuelReceiptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'deliveryDate' => Carbon::parse($this->delivery_date)->toDateString(),
            'actualMileage' => $this->actual_mileage,
            'product' => $this->product,
            'unit' => $this->unit,
            'tax' => $this->tax,
            'quantity' => $this->quantity,
            'totalNetto' => $this->total_netto,
            'pricePerUnit' => $this->price_per_unit,
            'totalBrutto' => $this->total_brutto,
            'fleetCarId' => $this->fleet_car_id,
            'managerId' => $this->manager_id
        ];
    }
}
