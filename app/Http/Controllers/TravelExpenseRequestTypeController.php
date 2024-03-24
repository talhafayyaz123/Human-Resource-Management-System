<?php

namespace App\Http\Controllers;

use App\Models\TravelExpenseRequestType as RequestType;
use App\Traits\CustomHelper;
use App\Utils\Logger;
use Illuminate\Http\Request;

class TravelExpenseRequestTypeController extends Controller
{
    use CustomHelper;

    /**
     *Runs when instance is initiated
     */

    public function __construct()
    {
        $this->middleware('check.permission:travel-expense-request-type.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:travel-expense-request-type.create', ['only' => ['store']]);
        $this->middleware('check.permission:travel-expense-request-type.edit', ['only' => ['update']]);
        $this->middleware('check.permission:travel-expense-request-type.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $per_page = $request->perPage;

        $model = new RequestType;
        $models = $model->when(isset($request->search), function ($query) use ($request) {
            $query->where("name", "LIKE", "%" . $request->search . "%");
        })->paginate($per_page);

        $model_collect = $models->map(function ($item) {
            return [
                'id' => $item->id,
                "name" => $item->name,
                "approvalLevel1" => $item->approval_level_1,
                "approvalLevel2" => $item->approval_level_2,
                "approvalLevel3" => $item->approval_level_3,
                "customerSpecific" => $item->customer_specific,
                "projectSpecific" => $item->project_specific,
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
            "name" => "required",
        ]);

        //Create Request Type
        $model = new RequestType;
        $this->saveData($model, $request);

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
        $model = RequestType::find($id);
        return response()->json(["data" => [
            'id' => $model->id,
            "name" => $model->name,
            "approvalLevel1" => $model->approval_level_1,
            "approvalLevel2" => $model->approval_level_2,
            "approvalLevel3" => $model->approval_level_3,
            "customerSpecific" => $model->customer_specific,
            "projectSpecific" => $model->project_specific,
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
        ]);

        //Create Request Type
        $model = RequestType::find($id);
        $this->saveData($model, $request);

        return response()->json(['message' => 'Record updated successfully.'], 200);
    }

    /**
     * Saves the data based on provided resource item
     *
     * @param   object  $model
     * @param   object  $request
     * @param   array   Response
     */
    public function saveData($model, $request)
    {
        $model->name = $request->name;
        $model->approval_level_1 = $request->approvalLevel1;
        $model->approval_level_2 = $request->approvalLevel2;
        $model->approval_level_3 = $request->approvalLevel3;
        $model->customer_specific = $request->customerSpecific;
        $model->project_specific = $request->projectSpecific;
        $model->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RequestType::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }
}
