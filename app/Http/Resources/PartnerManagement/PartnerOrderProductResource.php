<?php

namespace App\Http\Resources\PartnerManagement;

use App\Models\GlobalSetting;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerOrderProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->product_id,
            'partnerOrderProductId' => $this->id,
            'type' => $this->product?->payment_details_type,
            'componentType' => $this->type,
            'productId' => $this->product_id,
            'name' => $this->product_name ?? $this->product?->name,
            'productName' => $this->product_name ?? $this->product?->name,
            'articleNumber' => $this->product?->article_number,
            'quantity' => $this->quantity,
            'hourlyRate' => $this->hourly_rate,
            'salePrice' => $this->sale_price,
            'maintenanceRate' => $this->maintenance_rate,
            'tax' => $this->tax,
            'discount' => $this->discount,
            'partnerPrice' => $this->partner_price,
            'totalNetto' => $this->total_netto,
            'totalAmount' => $this->total_amount,
            'paymentPeriod' => $this->paymentPeriod ? [
                'id' => $this->paymentPeriod->id,
                'name' => $this->paymentPeriod->name,
            ] : null,
            'pricePerPeriod' => $this->price_per_period,
            'duration' => $this->duration,
            'additionalDescription' => $this->additional_description,
        ];
    }
}
