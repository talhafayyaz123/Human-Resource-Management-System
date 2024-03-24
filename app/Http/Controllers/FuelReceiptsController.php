<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Http\Requests\FuelReceiptRequest;
use App\Http\Resources\FuelReceiptResource;
use App\Models\FleetCar;
use App\Models\FuelReceipt;
use App\Services\FleetManagement\FuelReceiptsService;
use App\Traits\CustomHelper;
use Carbon\Carbon;

class FuelReceiptsController extends Controller
{
    use CustomHelper;
    public $fuelReceiptsService;

    public function __construct(FuelReceiptsService $fuelReceiptsService)
    {
        $this->middleware('check.permission:fuel-monitoring.show', ['show']);
        $this->middleware('check.permission:fuel-monitoring.create', ['store']);
        $this->fuelReceiptsService = $fuelReceiptsService;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JSON
     */
    public function store(FuelReceiptRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $fuel_receipt = $this->fuelReceiptsService->createFuelReceipt($data);
        if ($fuel_receipt) {
            $content = [
                'moduleAction' => "createFuelReceipt",
                'data' => $fuel_receipt,
            ];
            GlobalSettingHelper::sendEloAPIRequest($content);
        }
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Fuel Receipt'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fleet_car = FleetCar::findOrFail($id);
        $fuel_receipts = $fleet_car->fuelReceipt()->orderBy('created_at', 'asc')->get();
        $fuel_receipts_collection = $this->fuelReceiptsService->show($fuel_receipts);
        $total_quantity = $fuel_receipts_collection->sum('quantity');
        $total_netto = $fuel_receipts_collection->sum('totalNetto');
        $total_brutto = $fuel_receipts_collection->sum('totalBrutto');
        $tax = $fuel_receipts_collection->sum('tax');
        return response()->json([
            'licenceNumber' => $fleet_car->licence_number,
            'driverId' => $fleet_car->fleetDriver?->last()?->user_id ?? '',
            'vim' => $fleet_car->vim,
            'model' => $fleet_car->model,
            'fuelReceipts' => $fuel_receipts_collection,
            'totalQuantity' => number_format($total_quantity, 2),
            'totalNetto' => $total_netto,
            'totalBrutto' => round($total_brutto, 2),
            'tax' => $tax
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JSON
     */
    public function update(FuelReceiptRequest $request, $id)
    {
        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $fuel_receipt = FuelReceipt::findOrFail($id);
        $update = $this->fuelReceiptsService->updateFuelReceipt($fuel_receipt, $data);
        if ($update) {
            $content = [
                'moduleAction' => "updateFuelReceipt",
                'data' => $update,
            ];
            GlobalSettingHelper::sendEloAPIRequest($content);
        }
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Fuel Receipt'])], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JSON
     */
    public function destroy($id)
    {
        $fuel_receipt = FuelReceipt::findOrFail($id);
        $this->fuelReceiptsService->deleteFuelReceipt($fuel_receipt);
        if ($fuel_receipt) {
            $fuel_receipt->deleted_at = Carbon::now()->toIso8601ZuluString();
            $content = [
                'moduleAction' => "DeleteFuelReceipt",
                'data' => $fuel_receipt,
            ];
            GlobalSettingHelper::sendEloAPIRequest($content);
        }
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Fuel Receipt'])], 200);
    }
}
