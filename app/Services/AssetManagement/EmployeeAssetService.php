<?php

namespace App\Services\AssetManagement;

use App\Models\AssetList;
use App\Traits\SetGlobalNumber;

class EmployeeAssetService
{
    use SetGlobalNumber;
    /**
     * Store a new asset in the asset list.
     *
     * @param array $data The data for creating the asset.
     * @return AssetList The created asset instance.
     */
    public function storeAssetList(array $data)
    {
        // Validate and create the asset
        $asset = new AssetList;
        $number = $this->globalNumber($asset, 'asset_list', 'AL', 1000);
        $asset->employee_id = $data['employee_id'];
        $asset->asset_number = $number;
        $asset->save();


        return $asset;
    }

    /**
     * Update an existing asset in the asset list.
     *
     * @param AssetList $model The asset to be updated.
     * @param array $data The data for updating the asset.
     * @return AssetList The updated asset instance.
     */
    public function updateAssetList(AssetList $asset, array $data)
    {
        // Update and save the asset
        $asset->employee_id = $data['employee_id'];
        $asset->save();
        return $asset;
    }

    public function saveEmployeeText(AssetList $asset_list, $data)
    {
        if (!empty($data['employee_text'])) {
            $employee_texts_id = collect($data['employee_text'])->map(function ($item) {
                return $item['id'];
            });
            $asset_list->assetEmployeeTexts()->sync($employee_texts_id);
        }
        return $asset_list;
    }
    public function detachEmployeeText(AssetList $asset_list)
    {
        if ($asset_list->assetEmployeeTexts()->exists()) {
            $asset_list->assetEmployeeTexts()->detach();
        }
    }
}
