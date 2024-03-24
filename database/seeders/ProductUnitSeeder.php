<?php

namespace Database\Seeders;

use App\Constants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Facades\App\Http\Controllers\ProductUnitController;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Constants::UNITS_DATA as $data) {
            $request = new Request;
            $request->merge($data);
            $controller = ProductUnitController::store($request);
            if (!$controller) {
                Log::info('company seeder not saved');
            }
        }
    }
}
