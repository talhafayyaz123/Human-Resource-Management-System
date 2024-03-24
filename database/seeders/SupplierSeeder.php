<?php

namespace Database\Seeders;

use App\Constants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Facades\App\Http\Controllers\SupplierController;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Constants::SUPPLIERS as $supplier) {
            $request = new Request;
            $request->merge($supplier);
            $controller = SupplierController::store($request);
            if (!$controller) {
                Log::info('company seeder not saved');
            }
        }
    }
}
