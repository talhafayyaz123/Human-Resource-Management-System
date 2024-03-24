<?php

namespace App\Http\Controllers;

use App\Models\ProductUnit;
use App\Utils\Logger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Request as staticRequest;

class ProductUnitController extends Controller
{
    /**
     * Runs when instance is initiated
     */
    use CustomHelper;
    public function __construct()
    {
        $this->middleware('check.permission:product-unit.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:product-unit.create', ['only' => ['store']]);
        $this->middleware('check.permission:product-unit.edit', ['only' => ['update']]);
        $this->middleware('check.permission:product-unit.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $units = new ProductUnit();
        if ($sort_by && $sort_order) {
            $units = $this->applySortingBeforePagination($units, $sort_by, $sort_order);
        }
        $units = $units->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($unit) => [
                'id' => $unit->id,
                'name' => $unit->name,
                'shortName' => $unit->short_name,
                'createdAt' => Carbon::parse($unit->created_at)->format('d/m/y'),
            ]);
        return response()->json(['units' => $units]);
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
            "name" => "required|string",
            "shortName" => "required|string"
        ]);

        //Create Product unit
        $model = new ProductUnit;
        $model->name = $request->name;
        $model->short_name = $request->shortName;
        $model->save();
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Product unit'])], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $unit = ProductUnit::where("id", $id)->first();
        return response()->json(['unit' => [
            'id' => $unit->id,
            "name" => $unit->name,
            "shortName" => $unit->short_name
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
            "shortName" => "required|string"
        ]);

        $model = ProductUnit::where(["id" => $id])->first();
        $model->name = $request->name;
        $model->short_name = $request->shortName;
        $model->save();
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProductUnit::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }
}
