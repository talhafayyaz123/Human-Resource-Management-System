<?php

namespace App\Services\AssetManagement;

use App\Models\Asset;
use App\Models\AssetArticle;
use App\Traits\SetGlobalNumber;
use Carbon\Carbon;

class AssetArticleService
{
    use SetGlobalNumber;

    /**
     * Create a new asset article.
     *
     * @param array $data The data to create the asset article.
     * @return AssetArticle The created asset article instance.
     */
    public function create(array $data)
    {
        $model = '';
        collect($data['assetArticles'] ?? null)->map(function ($article) use ($data, &$model) {
            $model = new AssetArticle();
            $model->article_number = $this->globalNumber($model, 'article_number', $data['assetId'], 1000);
            $model->asset_id = $data['assetId'];
            $model->serial_no = $article['serialNo'];
            $model->inventory_status = $article['inventoryStatus'];
            $model->asset_delivery_id = $data['assetDelivery'];
            $model->save();
        });
        return $model;
    }

    /**
     * Update an existing asset article.
     *
     * @param AssetArticle $model The asset article instance to be updated.
     * @param array $data The updated data for the asset article.
     * @return AssetArticle The updated asset article instance.
     */
    public function update(AssetArticle $model, array $data)
    {
        // Update the attributes of the asset article with the provided data
        $model->update($data);

        // Return the updated asset article instance
        return $model;
    }
}
