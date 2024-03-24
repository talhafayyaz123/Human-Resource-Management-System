<?php

namespace App\Http\Controllers;

use App\Models\ReportCategory;
use App\Utils\Logger;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Request as staticRequest;

class ReportCategoryController extends Controller
{

    /**
     * Runs when instance is initiated
     */
    use CustomHelper;
    public function __construct()
    {
        $this->middleware('check.permission:report-category.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:report-category.create', ['only' => ['store']]);
        $this->middleware('check.permission:report-category.edit', ['only' => ['update']]);
        $this->middleware('check.permission:report-category.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
       
        $model = new ReportCategory;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $models = $model->orderBy('created_at')
            ->filter($request->only('search', 'status'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            });

        return response()->json($models);
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
            "name" => "required|max:50",
        ]);

        //Create report category
        $model = new ReportCategory;
        $this->saveData($model, $request);

        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Report category'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = ReportCategory::where('id', $id)->first();
        return response()->json([
            'modelData' => [
                'id' => $model->id,
                "name" => $model->name,
            ]
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
        $request->validate([
            "name" => "required|max:50",
        ]);

        //Update item
        $model = ReportCategory::where("id", $id)->first();
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
        $model = ReportCategory::find($id);
        $model->delete();
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
        $model = ReportCategory::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }
}
