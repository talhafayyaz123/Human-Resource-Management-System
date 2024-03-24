<?php

namespace App\Services\FleetManagement;

use App\Models\FuelReceipt;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class FuelReceiptsService
{
    /**
     * Create a new fuel receipt.
     *
     * @param  array  $data
     * @return \App\Models\FuelReceipt
     */
    public function createFuelReceipt(array $data)
    {
        $fuel_receipt = FuelReceipt::create($data);
        $fuel_receipt->fleetCar()->update(['updated_at' => $fuel_receipt->created_at, 'total_mileage' => $fuel_receipt->actual_mileage]);
        return $fuel_receipt;
    }

    /**
     * Update the specified fuelReceipt car.
     *
     * @param  \App\Models\FuelReceipt  $FuelReceipt
     * @param  array  $data
     * @return \App\Models\FuelReceipt
     */
    public function updateFuelReceipt(FuelReceipt $fuelReceipt, array $data)
    {
        $fuelReceipt->update($data);
        return $fuelReceipt;
    }

    /**
     * Delete the specified fuel receipt.
     *
     * @param  \App\Models\FuelReceipt  $FuelReceipt
     * @return void
     */
    public function deleteFuelReceipt(FuelReceipt $fuelReceipt)
    {
        $fuelReceipt->delete();
    }
    /**
     * shows all the fuel receips.
     *
     * @param  Illuminate\Database\Eloquent\Collection  $fuelReceipts
     * @return Illuminate\Support\Collection
     */
    public function show(Collection $fuelReceipts)
    {
        $fuel_receipts_collection = $fuelReceipts->map(function ($fuel_receipt, $key) use ($fuelReceipts) {
            $last_mileage = $fuel_receipt->actual_mileage;
            $required_mileage = 0;
            $consume_per_hundred_kilometer = 0;
            $eur_per_hundred_kilometer = 0;
            if (isset($fuelReceipts[$key + 1])) {
                $next_fuel_receipt = $fuelReceipts[$key + 1];
                $actual_mileage = $next_fuel_receipt->actual_mileage;
                $required_mileage = $actual_mileage - $last_mileage;
                $quantity = $fuel_receipt->quantity;
                if ($required_mileage != 0)
                    $consume_per_hundred_kilometer = ($quantity / $required_mileage) * 100;
                $eur_per_hundred_kilometer = $consume_per_hundred_kilometer * $fuel_receipt->price_per_unit;
            }
            return [
                'id' => $fuel_receipt->id,
                'deliveryDate' => Carbon::parse($fuel_receipt->delivery_date)->toDateString(),
                'actualMileage' => $fuel_receipt->actual_mileage,
                'product' => $fuel_receipt->product,
                'unit' => $fuel_receipt->unit,
                'tax' => $fuel_receipt->tax,
                'quantity' => $fuel_receipt->quantity,
                'totalNetto' => $fuel_receipt->total_netto,
                'pricePerUnit' => $fuel_receipt->price_per_unit,
                'totalBrutto' => $fuel_receipt->total_brutto,
                'fleetCarId' => $fuel_receipt->fleet_car_id,
                'managerId' => $fuel_receipt->manager_id,
                'euroPerHundredKilometer' => number_format($eur_per_hundred_kilometer, 2, '.', ''),
                'consumePerHundredKilometer' => number_format($consume_per_hundred_kilometer, 2, '.', ''),
            ];
        });
        return $fuel_receipts_collection;
    }
}
