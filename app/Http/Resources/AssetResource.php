<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AssetResource extends JsonResource
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
            'assetNumber' => $this->asset_number,
            'assetName' => $this->asset_name,
            'assetType' => $this->asset_type,
            'supplierId' => $this->supplier_id,
            'storeArticle' => $this->store_article == 1 ? true : false,
            'priceNetto' => $this->price_netto,
            'assetArticles' => $this->assetArticles->map(function ($article) {
                return [
                    'id' => $article->id,
                    'assetName' => $this->asset_name,
                    'model' => $article->assetDelivery->model,
                    'serialNo' => $article->serial_no,
                    'deliveryDate' => $article->assetDelivery->delivery_date,
                    'storageLocation' => $article->assetDelivery->storage_location_id,
                    'inventoryStatus' => $article->inventory_status,
                    'assetId' => $this->id,
                    'assetListId' => $article->asset_list_id
                ];
            }),
            'assetImage' => $this->whenLoaded('file', $this->file()->exists() ? [
                'name' => $this->file->viewable_name, 'size' => $this->file->storage_size,
                'base64' => base64_encode(Storage::disk('public')->get('public/assets/files/' . $this->file->storage_name))
            ] : []),
            'modelName' => $request['modelName'],
            'existingAssets' => $this->assetArticles->count(),
            'inStock' => $this->assetArticles()->where('inventory_status', 'stock')->count(),
            'assignedAssets' => $this->assetArticles()->where('inventory_status', 'assigned')->count(),
            'description' => $this->asset_description,
            'manufacturer' => $this->manufacturer,
            'activeAsset' => $this->active_asset,
            'archivedAsset' => $this->archived_asset,
        ];
    }
}
