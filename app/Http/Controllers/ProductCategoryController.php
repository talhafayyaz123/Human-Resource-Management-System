<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Utils\Logger;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
    /**
     *Runs when instance is initiated
     */

    use CustomHelper;
    public function __construct()
    {
        $this->middleware('check.permission:product-category.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:product-category.create', ['only' => ['store']]);
        $this->middleware('check.permission:product-category.edit', ['only' => ['update']]);
        $this->middleware('check.permission:product-category.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $model = new ProductCategory();
        if ($request->productType) {
            $model->where('product_type', $request->productType)->orWhereNull('product_type');
        }
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $models = $model->orderBy('product_categories.created_at')->when(isset($request->search), function ($query) use ($request) {
            $query->where("name", "LIKE", "%" . $request->search . "%");
        })->paginate($per_page)->withQueryString()->through(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
            'defaultUnit' => $category->productUnit?->name ?? "-",
            'isDefaultOnOffers' => $category->is_default_on_offers,
            'productType' => $category->product_type,
            'serviceContingent' => $category->service_contingent,
        ]);

        return response()->json($models, 200);
    }

    /**
     * returns product categories based on product type
     * @param Request $request
     * @return JSONResponse
     */
    public function getByProductType(Request $request)
    {
        $model = collect([]);
        if ($request->productType) {
            $model = ProductCategory::where('product_type', $request->productType)->get();
        }
        return response()->json([
            "data" => $model
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "defaultUnit" => "required",
            "productType" => ['nullable', Rule::in(['software', 'service', 'pauschal', 'ams', 'cloud-software', 'hosting', 'traveling'])],
            "serviceContingent" => "nullable|boolean",
        ]);

        //Create Offer
        $model = new ProductCategory;
        $this->saveData($model, $request);

        return response()->json(['message' => 'Record created successfully.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = ProductCategory::where('id', $id)->first();
        return response()->json([
            'data' => [
                'id' => $model->id,
                'name' => $model->name,
                'defaultUnit' => $model->default_unit,
                'isDefaultOnOffers' => $model->is_default_on_offers,
                'productType' => $model->product_type,
                'serviceContingent' => $model->service_contingent,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required",
            "defaultUnit" => "required",
            "productType" => ['nullable', Rule::in(['software', 'service', 'pauschal', 'ams', 'cloud-software', 'hosting', 'traveling'])],
            "serviceContingent" => "nullable|boolean",
        ]);

        //Update Sale Offer
        $model = ProductCategory::where("id", $id)->first();
        $this->saveData($model, $request);
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }

    /**
     * Saves the data based on provided resource item
     *
     * @param object $model
     * @param object $request
     * @param array   Response
     */
    public function saveData($model, $request)
    {
        $model->name = $request->name;
        $model->default_unit = $request->defaultUnit;
        $model->is_default_on_offers = $request->isDefaultOnOffers;
        $model->product_type = @$request->productType;
        $model->service_contingent = @$request->serviceContingent ?? 0;
        $model->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = ProductCategory::find($id);
        $model->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }
}
