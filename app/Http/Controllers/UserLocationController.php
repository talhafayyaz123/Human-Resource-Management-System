<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Models\UserLocation;
use App\Traits\CustomHelper;
use App\Utils\Logger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserLocationController extends Controller
{
    use CustomHelper;
    /**
     * Run on instantiate
     */
    public function __construct()
    {
        // $this->middleware('check.permission:user-location.list', ['only' => ['show']]);
        $this->middleware('check.permission:user-location.create', ['only' => ['store']]);
        $this->middleware('check.permission:user-location.edit', ['only' => ['update']]);
        $this->middleware('check.permission:user-location.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage;

        $model = new UserLocation;

        $get_permissions = $request->get('auth_user_permissions');
        if (!in_array('user-location.list', $get_permissions)) {
            $user_id = $request->get('auth_user_id');
            $model = $model->whereHas('jobData.user', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        }
        $models = $model->when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })->paginate($per_page);

        $model_collect = $models->map(function ($item) {
            return [
                'id' => $item->id,
                "name" => $item->name,
                "street" => $item->street,
                "address" => $item->address,
                "zipCode" => $item->zip_code,
                "state" => $item->state,
                "city" => $item->city,
                "country" => $item->country
            ];
        });
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model_collect = $this->applySorting($model_collect, $sort_by, $sort_order);
        }
        return response()->json([
            'data' => $model_collect,
            'links' => $models->links(),
            'meta' => [
                'current_page' => $models->currentPage(),
                'from' => $models->firstItem(),
                'last_page' => $models->lastPage(),
                'path' => request()->url(),
                'per_page' => $models->perPage(),
                'to' => $models->lastItem(),
                'total' => $models->total(),
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "street" => "required",
            "name" => "required",
        ]);

        //Create Terms Of Payment
        $model = new UserLocation;
        $this->saveData($model, $request, 'createLocation');

        return response()->json(['message' => 'Record created successfully.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = UserLocation::find($id);
        return response()->json(["data" => [
            'id' => $model->id,
            "name" => $model->name,
            "street" => $model->street,
            "address" => $model->address,
            "zipCode" => $model->zip_code,
            "state" => $model->state,
            "city" => $model->city,
            "country" => $model->country
        ]]);
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
        $request->validate([
            "name" => "required",
            "street" => "required",
        ]);

        //Create Terms Of Payment
        $model = UserLocation::find($id);
        $this->saveData($model, $request, 'updateLocation');

        return response()->json(['message' => 'Record updated successfully.'], 200);
    }


    /**
     * Saves the data based on provided resource item
     *
     * @param   object  $model
     * @param   object  $request
     * @param   array   Response
     */
    public function saveData($model, $request, $action = null)
    {
        $model->name = $request->name;
        $model->street = $request->street;
        $model->address = $request->address;
        $model->zip_code = $request->zipCode;
        $model->state = $request->state;
        $model->city = $request->city;
        $model->country = $request->country;
        $model->save();
        $content = [
            'moduleAction' => $action,
            'data' => $model->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $model);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = UserLocation::find($id);
        $model->delete();
        $model->deleted_at = Carbon::now()->toIso8601ZuluString();
        $content = [
            'moduleAction' => 'deleteLocation',
            'data' => $model->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    /**
     * Restore the previously deleted source.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $model = UserLocation::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }
}
