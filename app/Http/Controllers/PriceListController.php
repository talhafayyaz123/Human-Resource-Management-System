<?php

namespace App\Http\Controllers;

use App\Models\PriceList;
use Carbon\Carbon;
use App\Traits\CustomHelper;
use Exception;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    use CustomHelper;
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $prices = new PriceList();
        if ($sort_by && $sort_order) {
            $prices = $this->applySortingBeforePagination($prices, $sort_by, $sort_order);
        }
        $prices = $prices->orderBy('created_at')
            ->filter($request->only('search', 'status'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(function ($price) {
                return [
                    'id' => $price->id,
                    'name' => $price->name,
                    'isDefault' => $price->is_default,
                    'status' => $price->status,
                    'createdAt' => Carbon::parse($price->created_at)->format('d/m/y'),
                    'productSoftwareName' => $price->productSoftware?->name,
                    'productSoftwareId' => $price->product_software_id,
                    'type' => $price->type
                ];
            });

        return response()->json(['prices' => $prices]);
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
            'name' => 'required|string',
            'isDefault' => 'required|boolean',
            'status' => 'required|in:active,inactive',
            'productSoftwareId' => 'nullable|exists:product_software,id',
            'type' => 'required|in:docshero_customer_price_list,docshero_partner_price_list,partner_price_list'

        ]);

        // Check if there is already a default price for the selected product software
        if ($request->isDefault) {
            $product_price = PriceList::where('product_software_id', $request->productSoftwareId)->where('is_default', true);
            if ($product_price->exists()) {
                throw new Exception("Already a default price exists with this software");
            }
        }

        if($request->type === 'docshero_partner_price_list'){
            $product_price = PriceList::where(['type' => 'docshero_partner_price_list', 'status' => 'active'])->get();
            if(count($product_price) > 0){
                foreach ($product_price as $product){
                    $product->status = 'inactive';
                    $product->save();
                }
            }
        }

        // Create Product price
        PriceList::create([
            'name' => $request->name,
            'is_default' => $request->isDefault,
            'status' => $request->status,
            'product_software_id' => $request->productSoftwareId ?? null,
            'partnerId' => $request->partnerId['id'] ?? null,
            'type' => $request->type
        ]);

        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Product price'])], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $price = PriceList::find($id);

        if (!$price) {
            return response()->json(['error' => 'Price not found'], 404);
        }

        return response()->json(['price' => [
            'id' => $price->id,
            'name' => $price->name,
            'isDefault' => $price->is_default,
            'status' => $price->status,
            'productSoftware' => $price->product_software_id,
            'partnerId' => $price->partner_id ?? null,
            'type' => $price->type
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
            'name' => 'required|string',
            'isDefault' => 'required|boolean',
            'status' => 'required|in:active,inactive',
            'productSoftwareId' => 'nullable|exists:product_software,id',
            'type' => 'required|in:docshero_customer_price_list,docshero_partner_price_list,partner_price_list'
        ]);

        $price = PriceList::find($id);

        if (!$price) {
            return response()->json(['error' => 'Price not found'], 404);
        }

        // Check if there is already a default price for the selected product software
        if ($request->isDefault) {
            $product_price = PriceList::where('product_software_id', $request->productSoftwareId)
                ->where('id', '!=', $price->id)->where('is_default', true);
            if ($product_price->exists()) {
                throw new Exception("Already a default price exists with this software");
            }
        }

        $price->name = $request->name;
        $price->is_default = $request->isDefault;
        $price->status = $request->status;
        $price->partner_id = $request->partnerId['id'] ?? null;
        $price->type = $request->type;
        $price->product_software_id = $request->productSoftwareId ?? null;
        $price->save();

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
        $price = PriceList::find($id);

        if (!$price) {
            return response()->json(['error' => 'Price not found'], 404);
        }

        $price->delete();

        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }


    /**
     * Retrieve prices for a given product software ID.
     *
     * @param int $id The ID of the product software.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the retrieved price data.
     */
    public function getPriceBySoftware(int $id)
    {
        // Retrieve price list items for the given product software ID
        $price_list = PriceList::where('product_software_id', $id)->where('status', 'active')->get();

        // Map the retrieved price list items to a formatted data structure
        $data = $price_list->map(function ($price) {
            return [
                'id' => $price->id,
                'name' => $price->name,
                'isDefault' => $price->is_default,
                'status' => $price->status,
                'productSoftware' => $price->product_software_id,

            ];
        });

        // Return a JSON response with the formatted price data
        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function priceList()
    {
        $price = PriceList::where(['type' => 'docshero_partner_price_list', 'status' => 'active'])->first();

        if (!$price) {
            return response()->json(['error' => 'Price not found'], 404);
        }

        return response()->json(['price' => [
            'id' => $price->id,
            'name' => $price->name,
            'isDefault' => $price->is_default,
            'productSoftware' => $price->product_software_id,
            'partnerId' => $price->partner_id ?? null,
        ]]);
    }
}
