<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelExpenseDayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'travelExpenseId' => $this->travel_expense_id,
            'isActive' => $this->is_active == 1,
            'date' => $this->date,
            'breakfast' => $this->breakfast == 1,
            'lunch' => $this->lunch == 1,
            'dinner' => $this->dinner == 1,
            'fromTime' => $this->from_time,
            'toTime' => $this->to_time,
            'expenseRate' => $this->expense_rate,
            'country' => $this->travelExpense?->arrivalCountry?->name
        ];
    }
}
