<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AssetArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'assetName' => $this->asset->asset_name,
            'articleNumber' => $this->article_number,
            'assetId' => $this->asset_id,
            'model' => $this->assetDelivery->model,
            'serialNo' => $this->serial_no,
            'deliveryDate' => $this->assetDelivery->delivery_date,
            'storageLocation' => $this->assetDelivery->storageLocation->storage_location,
            'inventoryStatus' => $this->inventory_status,
            'userId' => $this->user_id,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'assetImage' => $this->asset->file()->exists() ? [
                'name' => $this->asset->file->viewable_name, 'size' => $this->asset->file->storage_size,
                'base64' => base64_encode(Storage::disk('public')->get('public/assets/files/' . $this->asset->file->storage_name)),
            ] : [],
            'assetEmployeeList' => $this->employeeList?->asset_number,
            'employee' => $this->employeeList?->employee?->first_name . ' ' . $this->employeeList?->employee?->last_name,
        ];
    }
}
