<?php namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MinProductStoreRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'itemNumber' => $this->item_number,
            'productTitle' => $this->title,
            'shortDescription' => $this->short_description,
            'Date' => $this->creation_date,
            'sellPrice' => $this->sell_price,
            'status' => $this->status,
        ];
    }
}
