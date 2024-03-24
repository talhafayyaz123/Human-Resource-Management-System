<?php

namespace App\Http\Controllers;

use App\Models\FleetCar;
use App\Services\FleetManagement\FuelReceiptsService;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class CostMonitoringController extends Controller
{
    public $fuelReceiptsService;
    use CustomHelper;

    public function __construct(FuelReceiptsService $fuelReceiptsService)
    {

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
        $cost_monitorings = FleetCar::filter(staticRequest::only('search'))->get();

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $cost_monitoring_collections = $cost_monitorings->map(function ($car) {
            $fuel_receipts = $car->fuelReceipt()->orderBy('created_at')->get();
            $fuel_receipts_collection = $this->fuelReceiptsService->show($fuel_receipts);
            $total_quantity = $fuel_receipts_collection->sum('quantity');
            $total_euro = $fuel_receipts_collection->sum('totalBrutto');
            return [
                'id' => $car->id,
                'totalFuel' => number_format($total_quantity, 2, '.', ''),
                'licenceNumber' => $car->licence_number,
                'vim' => $car->vim,
                'brand' => $car->brand,
                'model' => $car->model,
                'leasingRate' => $car->leasing_rate,
                'totalEuro' => $total_euro,
            ];
        });
        if ($sort_by && $sort_order) {
            $cost_monitoring_collections =  $this->applySorting($cost_monitoring_collections, $sort_by, $sort_order);
        }
        return response()->json([
            'data' =>  $cost_monitoring_collections,
        ]);
    }
}
