<?php

namespace Database\Seeders;

use App\Constants;
use App\Models\ProductGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product_groups = Constants::PRODUCT_GROUPS;
        foreach ($product_groups as $product) {
            //Create product Group
            $model = new ProductGroup();

            $model->name = $product['name'];
            $model->save();
        }
    }
}
