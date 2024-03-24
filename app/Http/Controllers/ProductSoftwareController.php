<?php

namespace App\Http\Controllers;

use App\Models\ProductSoftware;
use App\Utils\Logger;
use Carbon\Carbon;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class ProductSoftwareController extends Controller
{

    /**
     * Runs when instance is initiated
     */
    use CustomHelper;
    public function __construct()
    {
        $this->middleware('check.permission:product-software.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:product-software.create', ['only' => ['store']]);
        $this->middleware('check.permission:product-software.edit', ['only' => ['update']]);
        $this->middleware('check.permission:product-software.delete', ['only' => ['destroy']]);
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
        $softwares = new ProductSoftware();
        if ($sort_by && $sort_order) {
            $softwares = $this->applySortingBeforePagination($softwares, $sort_by, $sort_order);
        }
        $softwares = $softwares->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($software) => [
                'id' => $software->id,
                'name' => $software->name,
                'createdAt' => Carbon::parse($software->created_at)->format('d/m/y'),
            ]);
        return response()->json(['softwares' => $softwares]);
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
        ]);

        //Create Product software
        $model = new ProductSoftware;
        $model->name = $request->name;
        $model->save();
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Product software'])], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $software = ProductSoftware::where("id", $id)->first();
        return response()->json(['software' => [
            'id' => $software->id,
            "name" => $software->name,
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

        $model = ProductSoftware::where(["id" => $id])->first();
        $model->name = $request->name;
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
        ProductSoftware::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }
}
