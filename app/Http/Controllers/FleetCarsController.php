<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Http\Requests\FleetCarsRequest;
use App\Http\Resources\FleetCarResource;
use App\Models\FleetCar;
use App\Models\FleetUvv;
use App\Services\FleetManagement\FleetCarsService;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;


class FleetCarsController extends Controller
{
    use CustomHelper;
    public FleetCarsService $fleetCarsService;

    public function __construct(FleetCarsService $fleetCarsService)
    {
        $this->middleware('check.permission:project.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:project.create', ['only' => ['store']]);
        $this->middleware('check.permission:project.edit', ['only' => ['update']]);
        $this->middleware('check.permission:project.delete', ['only' => ['destroy']]);
        $this->fleetCarsService = $fleetCarsService;
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
        $model = new FleetCar();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        // Paginate the FleetCar records
        $fleet_cars = $model->filter(staticRequest::only('search', 'fuelType', 'status', 'driverId'))->paginate(25);
        // Return the paginated FleetCarResource
        return response()->json([
            'data' => FleetCarResource::collection($fleet_cars),
            'links' => $fleet_cars->links(),
            'current_page' => $fleet_cars->currentPage(),
            'from' => $fleet_cars->firstItem(),
            'last_page' => $fleet_cars->lastPage(),
            'path' => request()->url(),
            'per_page' => $fleet_cars->perPage(),
            'to' => $fleet_cars->lastItem(),
            'total' => $fleet_cars->total(),
        ]);
    }
    /**
     * Stores the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(FleetCarsRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $fleet_car = $this->fleetCarsService->createFleetCar($data);
        if ($fleet_car) {
            $content = [
                'moduleAction' => "createFleetCar",
                'data' => [
                    "fleetCar" => $fleet_car->toArray(),
                    "fleetDriver" => $fleet_car->fleetDriver?->toArray()
                ],
            ];
            GlobalSettingHelper::sendEloAPIRequest($content, $fleet_car);
        }
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Fleet Car']), 'id' => $fleet_car->id], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find the FleetCar record by ID
        $fleetCar = FleetCar::findOrFail($id);

        // Return a single FleetCarResource for the found record
        return new FleetCarResource($fleetCar);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(FleetCarsRequest $request, $id)
    {
        $validated_data = $request->validated();
        $fleet_car = FleetCar::findOrFail($id);
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $fleet_car = $this->fleetCarsService->updateFleetCar($fleet_car,  $data);
        if ($fleet_car) {
            $content = [
                'moduleAction' => "updateFleetCar",
                'data' => [
                    "fleetCar" => $fleet_car->toArray(),
                    "fleetDriver" => $fleet_car->fleetDriver?->toArray()
                ],
            ];
            GlobalSettingHelper::sendEloAPIRequest($content, $fleet_car);
        }
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Fleet Car']), 'id' => $fleet_car->id], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fleet_car = FleetCar::findOrFail($id);
        $this->fleetCarsService->deleteFleetCar($fleet_car);
        if ($fleet_car) {
            $fleet_car->deleted_at = Carbon::now()->toIso8601ZuluString();
            $content = [
                'moduleAction' => "deleteFleetCar",
                'data' => [
                    "fleetCar" => $fleet_car->toArray(),
                    "fleetDriver" => $fleet_car->fleetDriver?->toArray()
                ],
            ];
            GlobalSettingHelper::sendEloAPIRequest($content);
        }
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Fleet Car'])], 200);
    }

    /**
     * Uploads Fleet Documents.
     *
     * @param  int  $id,Request $request
     * @return \Illuminate\Http\Response
     */
    public function uploadFleetDocuments(Request $request)
    {
        $request->validate(['id' => 'required']);
        $model = FleetCar::findOrFail($request->id);
        $files = $request->uploadedFiles;
        $this->fleetCarsService->uploadFiles($model, $files);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Fleet Car Documents'])], 200);
    }

    /**
     * Update Fleet Documents.
     *
     * @param  int  $id,Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateFleetDocuments(Request $request)
    {
        $request->validate(['id' => 'required']);
        $model = FleetCar::findOrFail($request->id);
        $files = $request->uploadedFiles;
        $this->fleetCarsService->updateUploadedFiles($model, $files);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Fleet Car Documents'])], 200);
    }


    public function createUvv(Request $request)
    {
        $request->validate(['id' => 'required']);
        $fleet_car = FleetCar::findOrFail($request->id);
        $model = new FleetUvv();
        $model->date = isset($request->date) ? Carbon::parse($request->date) : null;
        $model->next_date = isset($request->nextDate) ? Carbon::parse($request->nextDate) : null;
        $model->manager_id = $request->managerId ?? null;
        $model->fleet_car_id = $fleet_car->id;
        $model->save();
        $files = $request->uploadedFiles ?? null;
        if (isset($files)) {
            $this->fleetCarsService->uploadFiles($model, $files);
        }
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Fleet UVV'])], 200);
    }
}
