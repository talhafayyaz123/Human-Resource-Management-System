<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductStoreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'itemNumber' => $this->item_number,
            'productTitle' => $this->product_title,
            'sellPrice' => $this->sell_price,
            'shortDescription' => $this->short_description,
            'longDescription' => $this->long_description,
            'isVisibleToCustomer' => $this->is_visible_for_customer ? true : false,
            'isVisibleToPartner' => $this->is_visible_for_partner ? true : false,
            'rattingAvg' => $this->reviews?->avg('ratting'),
            'totalReviews' => $this->reviews?->count(),
            'author' => $this->partner ? [
                'id' => $this->partner->id,
                'companyName' => $this->partner->company_name,
            ] : [],
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                ];
            }),
            'productImages' => $this->images?->map(function ($image) {
                return [
                    'viewableName' => $image->viewable_name,
                    'url' => $this->getImageUrl($image),
                    'size' => $image->storage_size,
                ];
            }),
            'logo' => $this->partner?->image()?->exists() ? [
                'base64Url' => "data:" . Storage::disk('public')->mimeType('company/' .  $this->partner->image->storage_name) . ";base64," . base64_encode(Storage::disk('public')->get('company/' .  $this->partner->image->storage_name))
            ] : []
        ];
    }

    private function getImageUrl($image)
    {
        $file = null;
        if (isset($image->storage_name)) {
            $file = Storage::disk('public')->get('productStore/' . $image->storage_name);
        }
        if (!$file) {
            return response()->json();
        }
        $mime = Storage::disk('public')->mimeType('productStore/' . $image->storage_name);
        $base64 = base64_encode($file);
        return "data:$mime;base64,$base64";
    }
}
