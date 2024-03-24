<?php

namespace App\Http\Controllers;

use App\Models\ProductGroup;
use App\Utils\Logger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Request as staticRequest;

class ProductGroupController extends Controller
{
    /**
     * Runs when instance is initiated
     */
    use CustomHelper;
    public function __construct()
    {
        $this->middleware('check.permission:product-group.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:product-group.create', ['only' => ['store']]);
        $this->middleware('check.permission:product-group.edit', ['only' => ['update']]);
        $this->middleware('check.permission:product-group.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OAS\SecurityScheme(
     * securityScheme="Bearer",
     * type="http",
     * scheme="bearer"
     * )
     * @OA\Get(
     *      path="/groups",
     *      operationId="getProductGroupList",
     *      tags={"ProductGroups"},
     *      summary="Get list of Product Groups",
     *      description="Returns list of Product Groups",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new ProductGroup();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $groups = $model->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($group) => [
                'id' => $group->id,
                'name' => $group->name,
                'createdAt' => Carbon::parse($group->created_at)->format('d/m/y'),
            ]);
        return response()->json(['groups' => $groups]);
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

        //Create Product Group
        $model = new ProductGroup;
        $model->name = $request->name;
        $model->save();
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Product group'])], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = ProductGroup::where("id", $id)->first();
        return response()->json(['group' => [
            'id' => $group->id,
            "name" => $group->name,
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

        $model = ProductGroup::where(["id" => $id])->first();
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
        ProductGroup::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }
}
