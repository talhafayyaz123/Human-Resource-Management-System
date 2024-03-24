<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelExpenseBillResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'travelExpenseId' => $this->travel_expense_id,
            'invoiceType' => $this->invoiceType,
            'location' => $this->location,
            'fromDate' => $this->from_date,
            'toDate' => $this->to_date,
            'description' => $this->description,
            'attachments' => $this->attachments?->map(function($item){
                return [
                    'id' => $item->id,
                    'name' => $item->viewable_name,
                    'size' => $item->storage_size,
                    'storage_name' => $item->storage_name,
                ];
            })
        ];
    }
}
