<?php

namespace App\Http\Resources;

use App\Models\AssetEmployeeListText;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AssetListResource extends JsonResource
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
            'updatedAt' => $this->updated_at,
            'personalNumber' => $this->employee?->jobData?->personal_number,
            'name' => $this->employee?->first_name . ' ' . $this->employee?->last_name,
            'employeeId' => $this->employee_id,
            'location' => $this->employee?->jobData?->location?->street . ' ' . $this->employee?->jobData?->location?->state,
            'teamId' => $this->team_id,
            'assetNumber' => $this->asset_number,
            'moduleHistory' => ModuleHistoryResource::collection($this->moduleHistory),
            'assetArticles' => $this->assetArticles->map(function ($asset_article) {
                $asset = $asset_article->asset;
                return [
                    'id' => $asset_article->id,
                    'assetName' => $asset_article->asset?->asset_name,
                    'model' => $asset_article->assetDelivery->model,
                    'serialNo' => $asset_article->serial_no,
                    'inventoryStatus' => $asset_article->inventory_status,
                    'deliveryDate' => $asset_article->assetDelivery->delivery_date,
                    'storageLocation' => $asset_article->assetDelivery->storage_location_id,
                    'assetListId' => $asset_article->asset_list_id,
                    'assetImage' => $asset->file()->exists() ? [
                        'name' => $asset->file->viewable_name, 'size' => $asset->file->storage_size,
                        'base64' => base64_encode(Storage::disk('public')->get('public/assets/files/' . $asset->file->storage_name)),
                    ] : [],
                ];
            }),
            'employeeText' => AssetEmployeeListText::first()?->asset_employee_text
        ];
    }
}
