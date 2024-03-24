<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Facades\App\Http\Controllers\ProductSoftwareController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Constants;

class ProductSoftwareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Constants::SOFTWARE as $data) {
            $request = new Request;
            $request->merge($data);
            $controller = ProductSoftwareController::store($request);
            if (!$controller) {
                Log::info('product software seeder not saved');
            }
        }
    }
}
