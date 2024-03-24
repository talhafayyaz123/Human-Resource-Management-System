<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\File;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCategory::truncate();

        $json = File::get("database/data/ProductCategories.json");
        $categories = json_decode($json);

        foreach ($categories as $key => $value) {
            ProductCategory::create([
                "name" => $value->name,
                "is_default_on_offers" => $value->isDefaultOnOffers
            ]);
        }
    }
}
