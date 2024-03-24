<?php

namespace App\Services\AssetManagement;

use App\Models\Asset;
use App\Models\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AssetService
{


    /**
     * Get all assets.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllAssets($request)
    {
        return Asset::filter($request)->get();
    }
    /**
     * Store a new asset.
     *
     * @param array $data The data for the new asset.
     * @return Asset The created asset.
     */
    public function storeAsset(array $data)
    {

        $asset = new Asset();
        $data['store_article'] = $data['store_article'] ?? false;
        // Validate and create the asset
        $asset = $asset->create($data);
        if (isset($data['asset_image']) && !empty($data['asset_image'])) {
            $this->processImage($asset, $data['asset_image']);
        }
        return $asset;
    }


    public function processImage($model, $attachment): void
    {
        if (isset($model->file)) {
            $model->file()->delete();
        }

        $original_name = $attachment['name'];
        if (isset($attachment['base64'])) {
            $base64Decode = base64_decode($attachment['base64'], true);

            // Generate a unique file name
            $file_name_to_store = $model->id . '__' . time() . '.' . $original_name;

            // Save the decoded file to disk
            Storage::disk('public')->put('public/assets/files/' . $file_name_to_store, $base64Decode);

            $size = Storage::disk('public')->size('public/assets/files/' . $file_name_to_store);

            $uploaded_file = new UploadedFile();
            $uploaded_file->storage_name = $file_name_to_store;
            $uploaded_file->viewable_name = $original_name;
            $uploaded_file->storage_size = $size;
            $uploaded_file->fileable()->associate($model);
            $uploaded_file->save();
        }
    }

    /**
     * Update an existing asset.
     *
     * @param int $id The ID of the asset to update.
     * @param array $data The updated data for the asset.
     * @return Asset The updated asset.
     */
    public function updateAsset(int $id, array $data)
    {
        // Find the asset
        $asset = Asset::findOrFail($id);
        if (isset($data['asset_image'])) {
            $this->processImage($asset, $data['asset_image']);
        }
        $data['store_article'] = $data['store_article'] ?? false;
        // Update and save the asset
        $asset->update($data);

        return $asset;
    }

    /**
     * Delete an asset.
     *
     * @param int $id The ID of the asset to delete.
     * @return bool True if the asset was deleted successfully.
     */
    public function deleteAsset(int $id)
    {
        // Find the asset
        $asset = Asset::findOrFail($id);

        // Delete the asset
        $asset->delete();

        return true;
    }
}
