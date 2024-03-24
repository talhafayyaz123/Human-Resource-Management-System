<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Http\Requests\DriverLicenceRequest;
use App\Models\FleetDriver;
use App\Services\FleetManagement\FleetDriverLicenceCheckService;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DriverLicenceCheckController extends Controller
{

    use CustomHelper;
    public FleetDriverLicenceCheckService $fleetDriverLicenceCheckService;

    public function __construct(FleetDriverLicenceCheckService $fleetDriverLicenceCheckService)
    {
        $this->middleware('check.permission:drivers-licence.show', ['only' => ['show']]);
        $this->middleware('check.permission:drivers-licence.create', ['only' => ['store']]);
        $this->fleetDriverLicenceCheckService = $fleetDriverLicenceCheckService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DriverLicenceRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $driver_license = $this->fleetDriverLicenceCheckService->createDriverLicenceCheck($data);
        $content = [
            'moduleAction' => 'createDriverLicenseCheck',
            'data' => $driver_license,
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Fleet Driver Licence Check'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $driver = FleetDriver::findOrFail($id);
        $driver_data = [
            'id' => $driver->id,
            'userId' => $driver->user_id,
            'lastCheck' => isset($driver->driverLicenceChecks->last()->created_at) ?
                Carbon::parse($driver->driverLicenceChecks->last()->created_at)->toDateString()
                : Carbon::parse($driver->created_at)->toDateString(),
            'status' => $driver->status,
            'driverCheckList' => $driver->driverLicenceChecks->map(function ($driver_check) {
                return [
                    'id' => $driver_check->id,
                    'checkType' => $driver_check->check_type,
                    'licenceValid' => Carbon::parse($driver_check->licence_valid_until)->toDateString(),
                    'managerId' => $driver_check->manager_id,
                    'createdAt' => Carbon::parse($driver_check->created_at)->toDateString()
                ];
            }),
        ];
        return response()->json([
            'data' => $driver_data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
