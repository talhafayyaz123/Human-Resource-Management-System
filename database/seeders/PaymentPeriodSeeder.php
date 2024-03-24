<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Facades\App\Http\Controllers\PaymentPeriodController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Constants;

class PaymentPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Constants::PAYMENT_PERIOD_DATA as $data) {
            $request = new Request;
            $request->merge($data);
            $controller = PaymentPeriodController::store($request);
            if (!$controller) {
                Log::info('payment period seeder not saved');
            }
        }
    }
}
