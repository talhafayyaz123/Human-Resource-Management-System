<?php

use App\Models\Asset;
use App\Models\AssetArticle;
use App\Models\AssetDelivery;
use App\Models\StorageLocation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $assets = Asset::get();
        foreach ($assets as $asset) {
            $asset_articles = $asset->assetArticles;
            $grouped_asset_articles = $asset_articles->groupBy('delivery_date');
            foreach ($grouped_asset_articles as $delivery_date => $articles) {

                $asset_delivery = AssetDelivery::create([
                    'delivery_date' => $delivery_date,
                    'quantity' => $articles->count(),
                    'asset_id' => $asset->id,
                    'supplier_id' => $asset->supplier_id

                ]);
                foreach ($articles as $asset_article) {
                    $storage_location = StorageLocation::firstOrNew(['storage_location' => $asset_article->storage_location]);
                    $storage_location->save();
                    $asset_delivery->update(['model' => $asset_article->model]);
                    $asset_delivery->update(['storage_location_id' => $storage_location->id]);
                    $asset_article->update(['asset_delivery_id' => $asset_delivery->id]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
