<?php namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MinProductStoreResource extends JsonResource
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
            })
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
