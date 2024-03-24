<?php

namespace Database\Seeders;

use App\Constants;
use App\Models\ProductVersion;
use Facades\App\Services\ProductService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products_data = Constants::PRODUCT_CONSTANTS;
        foreach ($products_data as $product) {
            $data = ProductService::create($product);
            if (isset($data["id"])) {
                Log::info('Product seeder not saved');
            }
        }
    }
}
