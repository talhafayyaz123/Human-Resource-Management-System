<?php

namespace App\Http\Controllers;

use App\Models\CompanyLocation;
use App\Utils\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyLocationController extends Controller
{
    /**
     * Run on instantiate
     */
    public function __construct()
    {
        $this->middleware('check.permission:company-location.list', ['only' => ['getLocationByCompany']]);
        $this->middleware('check.permission:company-location.create', ['only' => ['store']]);
        $this->middleware('check.permission:company-location.edit', ['only' => ['update']]);
        $this->middleware('check.permission:company-location.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @param  int  $company_id
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/company-locations/company/{id}",
     *      operationId="GetCompanyLocationsRecord",
     *      tags={"Company Location"},
     *      summary="Get locations of company",
     *      description="Display a listing of the resource",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of company record",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
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
    public function getLocationByCompany($company_id)
    {
        $model = CompanyLocation::where('company_id', $company_id)->get();
        $data = $model->map(function ($item) {
            return [
                'id' => $item->id,
                'addressFirst' => $item->address_first,
                'addressSecond' => $item->address_second,
                'zip' => $item->zip,
                'companyId' => $item->companyId,
                'city' => $item->city,
                'country' => $item->country_id,
                'countryName' => $item->relatedCountry?->name,
                'state' => $item->state,
                'isHeadQuarters' => $item->is_head_quarters
            ];
        });
        return response()->json([
            'success' => true,
            'locations' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     *      path="/company-locations",
     *      operationId="StoreCompanyLocation",
     *       tags={"Company Location"},
     *      summary="Store company location record",
     *      description="Store a newly created resource in storage",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Json request",
     *    @OA\JsonContent(
     *       required={"addressFirst","zip", "companyId", "city", "country", "state"},
     *       @OA\Property(property="addressFirst", type="string"),
     *       @OA\Property(property="zip", type="string"),
     *      @OA\Property(property="companyId", type="string"),
     *       @OA\Property(property="city", type="string"),
     *     @OA\Property(property="country", type="string"),
     *     @OA\Property(property="state", type="string"),
     *     @OA\Property(property="addressSecond", type="string"),
     *    ),
     *     ),
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
    public function store(Request $request)
    {
        $request->validate([
            'addressFirst' => 'required',
            'zip' => 'required',
            'companyId' => 'required',
            'city' => 'required',
            'country' => 'required',
            'state' => 'required',
        ]);

        //Create Company Location
        $model = new CompanyLocation();
        $this->saveData($model, $request);

        return response()->json([
            'success' => true,
            'message' => 'Record has been created',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Put(
     *      path="/company-locations/{id}",
     *      operationId="UpdateCompanyLocation",
     *       tags={"Company Location"},
     *      summary="Update company location record",
     *      description="Update the specified resource in storage",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of company location record",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Json request",
     *    @OA\JsonContent(
     *       required={"addressFirst","zip", "companyId", "city", "country", "state"},
     *       @OA\Property(property="addressFirst", type="string"),
     *       @OA\Property(property="zip", type="string"),
     *      @OA\Property(property="companyId", type="string"),
     *       @OA\Property(property="city", type="string"),
     *     @OA\Property(property="country", type="string"),
     *     @OA\Property(property="state", type="string"),
     *     @OA\Property(property="addressSecond", type="string"),
     *    ),
     *     ),
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'addressFirst' => 'required',
            'zip' => 'required',
            'companyId' => 'required',
            'city' => 'required',
            'country' => 'required',
            'state' => 'required',
        ]);

        //Create Company Location
        $model = CompanyLocation::where('id', $id)->first();
        $this->saveData($model, $request);
        return response()->json([
            'success' => true,
            'message' => 'Record has been updated',
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
        $model->company_id = $request->companyId;
        $model->country_id = $request->country;
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
    /**
     * @OA\Delete(
     *      path="/company-locations/{id}",
     *      operationId="DeleteCompanyLocation",
     *       tags={"Company Location"},
     *      summary="Delete company location record",
     *      description="Remove the specified resource from storage",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of performance record entry",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
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
    public function destroy($id)
    {
        CompanyLocation::where('id', $id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Record has been deleted',
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
        $model = CompanyLocation::find($id);
        $model->restore();
        return response()->json([
            'success' => true,
            'message' => 'Record has been restored',
        ]);
    }
}
