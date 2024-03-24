<?php namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductStoreRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'itemNumber' => $this->item_number,
            'title' => $this->title,
            'shortDescription' => $this->short_description,
            'description' => $this->description,
            'approverNotes' => $this->approver_notes,
            'Date' => $this->creation_date,
            'sellPrice' => $this->sell_price,
            'discount' => $this->discount,
            'partnerPrice' => $this->partner_price,
            'status' => $this->status,
            'authorId' => $this->author_id,
            'isShowOnAdmin' => $this->is_show_on_admin,
            'partner' => $this->partner ? [
                'id' => $this->partner->id,
                'companyName' => $this->partner->company_name,
            ] : [],
            'product' => $this->product,
            'serviceDescription' => $this->service_description,
            'serviceHours' => $this->service_hours,
            'delimitation' => $this->delimitation,
            'productImages' => $this->images?->map(function ($image) {
                return [
                    'viewableName' => $image->viewable_name,
                    'url' => $this->getImageUrl($image),
                    'size'=>  Storage::disk('public')->size('productStoreRequest/' .$image->storage_name)
                ];
            }),
            'documentedImages' => $this->documentedImages?->map(function ($image) {
                return [
                    'viewableName' => $image->viewable_name,
                    'url' => $this->getImageUrl($image),
                    'size' =>  Storage::disk('public')->size('productStoreRequest/' . $image->storage_name)
                ];
            }),
            'scripts' => $this->scripts?->map(function ($image) {
                return [
                    'viewableName' => $image->viewable_name,
                    'url' => $this->getImageUrl($image),
                    'size'=>  Storage::disk('public')->size('productStoreRequest/' .$image->storage_name)
                ];
            })
        ];
    }

    private function getImageUrl($image)
    {
        $file = null;
        if (isset($image->storage_name)) {
            $file = Storage::disk('public')->get('productStoreRequest/' . $image->storage_name);
        }
        if (!$file) {
            return response()->json();
        }
        $mime = Storage::disk('public')->mimeType('productStoreRequest/' . $image->storage_name);
        $base64 = base64_encode($file);
        return "data:$mime;base64,$base64";
    }
}