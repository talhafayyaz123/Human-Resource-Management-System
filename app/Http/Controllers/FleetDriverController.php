<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Http\Requests\FleetDriverRequest;
use App\Http\Resources\FleetCarResource;
use App\Http\Resources\ModuleHistoryResource;
use App\Services\FleetManagement\FleetDriverService;
use App\Models\FleetCar;
use App\Models\FleetDriver;
use App\Models\FleetManagementIntervalSetting;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request as staticRequest;

class FleetDriverController extends Controller
{
    use CustomHelper;
    public $fleetDriverService;

    public function __construct(FleetDriverService $fleetDriverService)
    {
        $this->middleware('check.permission:fleet-drivers.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:fleet-drivers.create', ['only' => ['store']]);
        $this->middleware('check.permission:fleet-drivers.edit', ['only' => ['update']]);
        $this->fleetDriverService = $fleetDriverService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $config = config('services.users');
        $token = $request->bearerToken();
        $url = $config['vite_auth_service_url'];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($url . '/list-users', [
            'limit_start' => 0,
            'limit_count' => 25,
            'type' => 'employee'
        ]);
        $response = $response->json();
        $users = collect($response['data'] ?? []);
        $fleet_drivers = FleetDriver::all();

        $fleet_drivers = $fleet_drivers->map(function ($fleet_driver) use ($users) {
            $user = $users->firstWhere('id', $fleet_driver->user_id);
            $last_checkup = optional($fleet_driver->driverLicenceChecks->last()) ?? null;
            $next_checkup = '';
            $next_checkup_date = '';
            $last_checkup_date = '';
            if (!empty($last_checkup->created_at)) {
                $last_checkup_date = Carbon::parse($last_checkup->created_at);
                $next_checkup = $last_checkup_date->copy();
                $next_checkup_date = $next_checkup->toDateString();
                $last_checkup_date = $last_checkup_date->toDateString();
                if (Carbon::now() > Carbon::parse($last_checkup->licence_valid_until)) {
                    $fleet_driver->status = 'out_of_service';
                    $fleet_driver->save();
                    $status = 'Out of service';
                } else {
                    $fleet_driver->status = 'ready';
                    $fleet_driver->save();
                    $status = 'Ready';
                }
            } else {
                $status = 'New Driver';
                $fleet_driver->status = 'new';
                $fleet_driver->save();
            }

            $interval_settings_month = FleetManagementIntervalSetting::first();
            if (!empty($next_checkup) && isset($interval_settings_month->months)) {
                $next_checkup_date = $next_checkup->addMonths($interval_settings_month->months)->toDateString();
            }
            $licence_valid_until = '';
            $licence_check = $fleet_driver->driverLicenceChecks->last();
            if ($licence_check) {
                $licence_valid_until = Carbon::parse($licence_check->licence_valid_until)->toDateString();
            }
            return [
                'name' => ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''),
                'id' => $fleet_driver->id,
                'status' => $status,
                'lastCheckup' => $last_checkup_date,
                'nextCheckup' => $next_checkup_date,
                'userId' => $fleet_driver->user_id,
                'licenceValidUntil' => $licence_valid_until,
                'licencePlate' => $fleet_driver->fleetCar->pluck('licence_number'),
                'vehicleClasses' => $fleet_driver->vehicleClasses->map(function ($vehicle_class) {
                    return [
                        'id' => $vehicle_class->id,
                        'name' => $vehicle_class->name,
                    ];
                }),
            ];
        });
        if ($sort_by && $sort_order) {
            $fleet_drivers = $this->applySorting($fleet_drivers, $sort_by, $sort_order);
        }
        $search_term = $request->search;
        if ($search_term) {
            $fleet_drivers = $fleet_drivers->filter(function ($driver) use ($search_term) {
                // Replace 'name' with the actual column name you want to search in
                return Str::contains($driver['name'], $search_term);
            })->values();
        }
        return response()->json([
            'data' => $fleet_drivers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FleetDriverRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $data['status'] = 'new';
        $driver = $this->fleetDriverService->createFleetDriver($data);
        if (isset($data['fleet_car_id'])) {
            $driver->fleetCar()->attach(collect($data['fleet_car_id'])->pluck('id'));
        }
        if ($driver) {
            $content = [
                'moduleAction' => 'createFleetDriver',
                'data' => [
                    'driver' => $driver,
                    'fleetCar' => $driver?->fleetCar(),
                ]
            ];
            GlobalSettingHelper::sendEloAPIRequest($content, $driver);
        }
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Fleet Driver'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fleet_driver = FleetDriver::findOrFail($id);
        $fleet_driver = [
            'id' => $fleet_driver->id,
            'userId' => $fleet_driver->user_id,
            'carType' => $fleet_driver->car_type,
            'fleetCarId' => FleetCarResource::collection($fleet_driver->fleetCar ?? collect()),
        ];
        return response()->json([
            'data' => $fleet_driver,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FleetDriverRequest $request, $id)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $fleet_driver = FleetDriver::findOrFail($id);
        $driver = $this->fleetDriverService->updateFleetDriver($fleet_driver, $data);
        $current_fleet_car_ids = $driver->fleetCar->pluck('id') ?? [];

        $fleet_car_ids = collect($data['fleet_car_id'] ?? [])->pluck('id') ?? [];
        // Find the IDs that need to be attached (new records or missing records)
        $attach_ids = $fleet_car_ids->diff($current_fleet_car_ids ?? collect([]));

        // Find the IDs that need to be detached (existing records not present in the provided array)
        $detach_ids = $current_fleet_car_ids->diff($fleet_car_ids, collect([]));
        // Adding the deleted_at column timestamp

        if ($detach_ids->isNotEmpty()) {
            foreach ($detach_ids as $fleet_car_id) {
                $driver->fleetCar()->updateExistingPivot($fleet_car_id, ['deleted_at' => now()]);
            }
        }
        // Attach the new/missing records
        if ($attach_ids->isNotEmpty()) {
            $driver->fleetCar()->attach($attach_ids->all());
        }
        if ($driver) {
            $content = [
                'moduleAction' => 'updateFleetDriver',
                'data' => [
                    'driver' => $driver,
                    'fleetCar' => $driver?->fleetCar(),
                ]
            ];
            GlobalSettingHelper::sendEloAPIRequest($content, $driver);
        }
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Fleet Driver'])], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function driverCarHistory(Request $request)
    {
        $request->validate([
            'userId' => 'required|string'
        ]);
        $fleet_driver = FleetDriver::where('user_id', $request->userId)->first();
        $previously_owned_cars = $fleet_driver?->previouslyOwnedCars->map(function ($car) {
            return [
                'id' => $car->id,
                'licenceNumber' => $car->licence_number,
                'model' => $car->model,
                'brand' => $car->brand,
                'color' => $car->color
            ];
        });
        $owned_cars = $fleet_driver?->fleetCar->map(function ($car) {
            return [
                'id' => $car->id,
                'licenceNumber' => $car->licence_number,
                'model' => $car->model,
                'brand' => $car->brand,
                'color' => $car->color
            ];
        });
        return response()->json([
            'previouslyOwnedCars' => $previously_owned_cars,
            'ownedCars' => $owned_cars
        ]);
    }
}
