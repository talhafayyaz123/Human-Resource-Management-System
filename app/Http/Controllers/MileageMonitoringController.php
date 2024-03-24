<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request as staticRequest;
use App\Models\FleetCar;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MileageMonitoringController extends Controller
{
    use CustomHelper;
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $fleet_cars = FleetCar::filter(staticRequest::only('search'))->get();
        $cars_with_more_kilometers = 0;
        $cars_with_less_mileage = 0;
        $ok_cars = 0;
        $fleet_car_collections = $fleet_cars->map(function ($car) use (&$cars_with_more_kilometers, &$cars_with_less_mileage, &$ok_cars) {
            $years = $car->contract_period_months / 12;
            $total_miles = $car->miles * $years;
            $free_extra_kilometers = $car->getFreeExtraKilometers();
            $average_free_extra_kilometers = $free_extra_kilometers > 0 ? number_format($free_extra_kilometers / 3, 2, '.', '') : 0;
            $free_less_kilometers = $car->getFreeLessKilometers();
            $average_free_less_kilometers = $free_less_kilometers > 0 ? number_format($free_less_kilometers / 3, 2, '.', '') : 0;
            $first_sum = $car->getFirstSum();
            $second_sum = $car->getSecondSum();

            $max_miles = $total_miles + $average_free_extra_kilometers;
            $min_miles = $total_miles - $average_free_less_kilometers;
            $total_mileage = $car->total_mileage;

            $monthly_extra_mileage = $max_miles / $car->contract_period_months;
            $monthly_less_mileage = $min_miles / $car->contract_period_months;

            $start_date = Carbon::parse($car->leasing_start_date);
            $updated_at = Carbon::parse($car->updated_at);
            $months_till_now = $start_date->diffInMonths($updated_at);
            $actual_monthly_extra_mileage = $monthly_extra_mileage * $months_till_now;
            $actual_monthly_less_mileage =   $monthly_less_mileage * $months_till_now;

            if ($total_mileage > $actual_monthly_extra_mileage) {
                $cars_with_less_mileage = $cars_with_less_mileage + 1;
                $is_green = false;
                $total_potential = number_format(($total_mileage - $actual_monthly_extra_mileage) * ($first_sum / 100), 2, '.', '');
            } else if ($total_mileage < $actual_monthly_less_mileage) {
                $cars_with_more_kilometers = $cars_with_more_kilometers + 1;
                $is_green = true;
                $total_potential = number_format(($actual_monthly_less_mileage - $total_mileage) * ($second_sum / 100), 2, '.', '');
            } else {
                $ok_cars = $ok_cars + 1;
                $is_green = true;
                $total_potential = 0;
            }
            return [
                'contactPeriodMonths' => $car->contract_period_months,
                'licenceNumber' => $car->licence_number,
                'milesPerYear' => $car->miles,
                'vim' => $car->vim,
                'totalMileageTillNow' => $car->total_mileage,
                'driverId' => $car->fleetDriver?->last()?->user_id ?? '',
                'status' => $car->status,
                'isGreen' => $is_green,
                'totalPotential' => $total_potential,
                'monthsTillNow' => $months_till_now,

            ];
        });
        $vehicles_available = $fleet_car_collections->where('status', 'ready')->count();
        $total_saving_potential = $fleet_car_collections->sum('totalPotential');
        if ($sort_by && $sort_order) {
            $fleet_car_collections = $this->applySorting($fleet_car_collections, $sort_by, $sort_order);
        }

        return response()->json([
            'data' => $fleet_car_collections,
            'vehiclesAvailable' => $vehicles_available,
            'carsWithLessMileage' => $cars_with_less_mileage,
            'carsWithMoreKilometers' => $cars_with_more_kilometers,
            'carsWithExactKilometers' => $ok_cars,
            'totalSavingPotential' => number_format($total_saving_potential, 2, '.', '')

        ]);
    }
}
