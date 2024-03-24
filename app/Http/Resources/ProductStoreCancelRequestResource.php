<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductStoreCancelRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customerId' => $this->customer_id,
            'partnerId' => $this->partner_id,
            'reason' => $this->reason,
            'partner' => $this->partner ? [
                'id' => $this->partner->id,
                'companyName' => $this->partner->company_name,
            ] : [],
            'customer' => $this->customer ? [
                'id' => $this->customer->id,
                'companyName' => $this->customer->company_name,
            ] : [],
       
            'entries' => $this->entries?->map(function ($entry) {
                return [
                    'id' => $entry->id,
                    'productTitle' => $entry->product_title,
                ];
            }),


               ];
    }

}
