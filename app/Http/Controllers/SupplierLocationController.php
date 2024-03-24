<?php

namespace App\Http\Controllers;

use App\Models\SupplierLocation;
use App\Utils\Logger;
use Illuminate\Http\Request;

class SupplierLocationController extends Controller
{
    /**
     * Run on instantiate
     */
    public function __construct()
    {
        $this->middleware('check.permission:supplier-location.list', ['only' => ['getLocationBySupplier']]);
        $this->middleware('check.permission:supplier-location.create', ['only' => ['store']]);
        $this->middleware('check.permission:supplier-location.edit', ['only' => ['update']]);
        $this->middleware('check.permission:supplier-location.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @param  int  $supplier_id
     *
     * @return \Illuminate\Http\Response
     */
    public function getLocationBySupplier($supplier_id)
    {
        $model = SupplierLocation::where("supplier_id", $supplier_id)->get();
        $data = $model->map(function ($item) {
            return [
                "id" => $item->id,
                "addressFirst" => $item->address_first,
                "addressSecond" => $item->address_second,
                "zip" => $item->zip,
                "supplierId" => $item->supplierId,
                "city" => $item->city,
                "country" => $item->country,
                "countryName" => $item->countryRelation?->name,
                "state" => $item->state,
                "isHeadQuarters" => $item->is_head_quarters
            ];
        });
        return response()->json([
            "success" => true,
            "locations" => $data
        ]);
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
            "addressFirst" => "required",
            "zip" => "required",
            "supplierId" => "required",
            "city" => "required",
            "country" => "required",
            "state" => "required",
        ]);

        //Create Supplier Location
        $model = new SupplierLocation;
        $this->saveData($model, $request);

        return response()->json([
            "success" => true,
            'message' => trans('messages.record_saved_success', ['name' => 'Supplier location']),
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
            "addressFirst" => "required",
            "zip" => "required",
            "city" => "required",
            "country" => "required",
            "state" => "required",
        ]);

        //Create Supplier Location
        $model = SupplierLocation::where("id", $id)->first();
        $this->saveData($model, $request);
        return response()->json([
            "success" => true,
            "message" => "Record has been updated",
        ]);
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
        $model->address_first = $request->addressFirst;
        $model->address_second = $request->addressSecond;
        $model->city = $request->city;
        $model->zip = $request->zip;
        $model->supplier_id = $request->supplierId;
        $model->country = $request->country;
        $model->state = $request->state;
        $model->is_head_quarters = false;
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
        SupplierLocation::where('id', $id)->delete();
        return response()->json([
            "success" => true,
            "message" => "Record has been deleted",
        ]);
    }

    /**
     * Restore the previously deleted source.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $model = SupplierLocation::find($id);
        $model->restore();
        return response()->json([
            "success" => true,
            "message" => "Record has been restored",
        ]);
    }
}
