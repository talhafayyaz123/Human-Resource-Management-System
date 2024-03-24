<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelExpenseTransportationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'travelExpenseId' => $this->travel_expense_id,
            'drivenKilometers' => $this->driven_kilometer,
            'carType' => $this->car_type,
            'arrivalCity' => $this->travelExpense?->arrival_city,
            'departureCity' => $this->travelExpense?->departure_city,
            'fleetCarId' => $this->fleetCar ? [
                'id' => $this->fleetCar->id,
                'licenceNumber' => $this->fleetCar->licence_number
            ] : null,
            'amount' => $this->amount
        ];
    }
}
