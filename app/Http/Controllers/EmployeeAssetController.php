<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetListRequest;
use App\Http\Resources\AssetListResource;
use App\Models\AssetList;
use App\Models\FleetDriver;
use App\Models\UserProfileData;
use App\Services\AssetManagement\EmployeeAssetService;
use App\Traits\CustomHelper;
use Exception;
use Illuminate\Http\Request;

class EmployeeAssetController extends Controller
{
    use CustomHelper;
    protected $assetListService;

    /**
     * Create a new controller instance.
     *
     * @param AssetService $assetService The asset service instance.
     */
    public function __construct(EmployeeAssetService $assetListService)
    {
        $this->middleware('check.permission:employee-asset.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:employee-asset.create', ['only' => ['store']]);
        $this->middleware('check.permission:employee-asset.edit', ['only' => ['update']]);
        $this->middleware('check.permission:employee-asset.delete', ['only' => ['destroy']]);
        $this->assetListService = $assetListService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new AssetList();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        if (isset($request->search)) {
            $model = $model->where('asset_number', 'LIKE', '%' . $request->search . '%');
        }
        
        $asset_lists = $model->paginate($per_page);
        return AssetListResource::collection($asset_lists);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetListRequest $request)
    {
        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $asset_list = AssetList::where('employee_id', $data['employee_id'])->first();
        if (!empty($asset_list)) {
            throw new Exception('An Asset List Already Exists for this employee');
        }
        $asset_list = $this->assetListService->storeAssetList($data);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Employee Asset List']), 'id' => $asset_list->id], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $asset_lists = AssetList::findOrFail($id);
        return new AssetListResource($asset_lists);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AssetListRequest $request, $id)
    {
        $asset_list = AssetList::findOrFail($id);
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        if ($asset_list->employee_id != $data['employee_id']) {
            if (AssetList::where('employee_id', $data['employee_id'])->exists()) {
                throw new Exception('An Asset List Already Exists for this employee');
            }
        }
        $asset_list = $this->assetListService->updateAssetList($asset_list, $data);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Employee Asset List']), 'id' => $asset_list->id], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset_list = AssetList::findOrFail($id);
        $asset_list->delete();
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Asset Article'])], 200);
    }

    public function getEmployeeDetails(int $id)
    {
        $user_profile_data = UserProfileData::findOrFail($id);
        return response()->json(['employee' => [
            'name' => $user_profile_data->first_name . ' ' . $user_profile_data->last_name,
            'personalNumber' => $user_profile_data->jobData?->personal_number,
            'teams' => $user_profile_data->teams,
            'location' => $user_profile_data->jobData?->location
        ]]);
    }

    public function employeeAssets(Request $request)
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
        $asset_articles = [];
        $employee = UserProfileData::where('user_id', $request->userId)->first();
        if (!empty($employee)) {
            $asset_list = AssetList::where('employee_id', $employee->id)->first();
            $asset_articles = $asset_list->assetArticles ?? null;
            if (isset($asset_articles)) {
                $asset_articles = $asset_articles->map(function ($asset_article) {
                    return [
                        'id' => $asset_article->id,
                        'assetName' => $asset_article->asset?->asset_name,
                        'model' => $asset_article->model,
                        'serialNo' => $asset_article->serial_no,
                        'status' => $asset_article->inventory_status,
                        'storageLocation' => $asset_article->storage_location
                    ];
                });
            }
        }
        return response()->json([
            'previouslyOwnedCars' => $previously_owned_cars,
            'ownedCars' => $owned_cars,
            'assetArticles' => $asset_articles

        ]);
    }
}
