<?php

namespace App\Http\Controllers;

use App\Models\FleetCar;
use App\Services\FleetManagement\FuelReceiptsService;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class FuelMonitoringController extends Controller
{
    public $fuelReceiptsService;
    use CustomHelper;

    public function __construct(FuelReceiptsService $fuelReceiptsService)
    {
        $this->middleware('check.permission:fuel-monitoring.list');
        $this->fuelReceiptsService = $fuelReceiptsService;
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $fleet_cars = FleetCar::filter(staticRequest::only('search'))->get();
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $fleet_car_collections = $fleet_cars->map(function ($car) {
            $fuel_receipts = $car->fuelReceipt()->orderBy('created_at')->get();
            $fuel_receipts_collection = $this->fuelReceiptsService->show($fuel_receipts);
            $last_item = $fuel_receipts_collection->last();
            $fuel_receipts_except_last = $fuel_receipts_collection->except($fuel_receipts_collection->count() - 1);
            $total_quantity = $fuel_receipts_collection->sum('quantity');
            $euro_average = $fuel_receipts_except_last->avg('euroPerHundredKilometer');
            $consume_average = $fuel_receipts_except_last->avg('consumePerHundredKilometer');
            $total_euro = $fuel_receipts_collection->sum('totalBrutto');
            return [
                'id' => $car->id,
                'vim' => $car->vim,
                'licenceNumber' => $car->licence_number,
                'driverId' => $car->fleetDriver?->last()?->user_id ?? '',
                'actualMileage' => $last_item['actualMileage'] ?? '',
                'totalFuel' => number_format($total_quantity, 2, '.', ''),
                'euroAverage' => number_format($euro_average, 2, '.', ''),
                'consumeAverage' =>  number_format($consume_average, 2, '.', ''),
                'totalEuro' => number_format($total_euro, 2, '.', '')
            ];
        });
        if ($sort_by && $sort_order) {
            $fleet_car_collections =  $this->applySorting($fleet_car_collections, $sort_by, $sort_order);
        }
        return response()->json([
            'data' => $fleet_car_collections,
        ]);
    }
}
