<?php

use App\Models\Asset;
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

        $assets = Asset::all();
        $assets->map(function ($asset) {
            if (isset($asset->store_article)) {
                if ($asset->store_article == 'yes') {
                    $asset->store_article = true;
                } else {
                    $asset->store_article = false;
                }
                $asset->save();
            }
        });
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
