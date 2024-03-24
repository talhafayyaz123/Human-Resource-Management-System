<?php

namespace App\Http\Controllers;

use App\Models\SlaInfrastructure;
use Illuminate\Http\Request;

class SlaInfrastructureController extends Controller
{

    public function __construct()
    {
        $this->middleware('check.permission:sla.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:sla.create', ['only' => ['store']]);
        $this->middleware('check.permission:sla.edit', ['only' => ['update']]);
        $this->middleware('check.permission:sla.delete', ['only' => ['destroy']]);
    }

    /**
     * @OA\Get(
     *      path="/sla-infrastructure",
     *      operationId="getSlaInfrasturcture",
     *      tags={"Sla Infrastructure"},
     *      summary="Get list of infrasturctures",
     *      description="Returns list of infrasturctures",
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
        $slaInfrastructures = SlaInfrastructure::all();
        return response()->json([
            'data' => $slaInfrastructures->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'access' => $item->is_access ? true : false,
                    'includedUsers' => $item->user_included,
                    'additionalUser' => $item->additional_user_cost,
                    'category' => $item->category,
                    'price' => $item->price,
                ];
            })
        ]);
    }
    /**
     * @OA\Post(
     *      path="/sla-infrastructure",
     *      operationId="StoreSlaInfrasturcture",
     *      tags={"Sla Infrastructure"},
     *      summary="Store Sla Infrastructure record",
     *      description="Store a newly created resource in storage",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Json request",
     *    @OA\JsonContent(
     *       required={"infrastructureName","access", "includedUsers", "additionalUser"},
     *       @OA\Property(property="infrastructureName", type="string"),
     *       @OA\Property(property="access", type="boolean"),
     *      @OA\Property(property="includedUsers", type="string"),
     *       @OA\Property(property="additionalUser", type="string"),
     *     ),
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
            'infrastructureName' => 'required',
            'access' => 'required|boolean',
            'includedUsers' => 'required',
            'additionalUser' => 'nullable',
            'category' => 'required',
            'price' => 'required',
        ]);

        $data = [
            'name' => $request['infrastructureName'],
            'is_access' => $request['access'] ? 1 : 0,
            'user_included' => $request['includedUsers'],
            'additional_user_cost' => $request['additionalUser'],
            'category' => $request['category'],
            'price' => $request['price'],
        ];
        SlaInfrastructure::create($data);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'SLA Infrastructure'])], 200);
    }
    /**
     * @OA\Get(
     *      path="/sla-infrastructure/{id}",
     *      operationId="getSingleSlaInfrasturcture",
     *      tags={"Sla Infrastructure"},
     *      summary="Get single record of Sla infrasturcture",
     *      description="Display the specified resource",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of sla infrasturcture record",
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
    public function show($id)
    {
        $slaInfrastructure = SlaInfrastructure::find($id);
        return response()->json(['data' => [
            'id' => $slaInfrastructure->id,
            'name' => $slaInfrastructure->name,
            'access' => $slaInfrastructure->is_access ? true : false,
            'includedUsers' => $slaInfrastructure->user_included,
            'additionalUser' => $slaInfrastructure->additional_user_cost,
            'category' => $slaInfrastructure->category,
            'price' => $slaInfrastructure->price,
        ]]);
    }
    /**
     * @OA\Put(
     *      path="/sla-infrastructure/{id}",
     *      operationId="updateSlaInfrasturcture",
     *      tags={"Sla Infrastructure"},
     *      summary="Update Sla Infrastructure record",
     *      description="Update a resource in storage",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of sla infrasturcture record",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     *    @OA\RequestBody(
     *    required=true,
     *    description="Pass Json request",
     *    @OA\JsonContent(
     *       required={"infrastructureName","access", "includedUsers", "additionalUser"},
     *       @OA\Property(property="infrastructureName", type="string"),
     *       @OA\Property(property="access", type="boolean"),
     *      @OA\Property(property="includedUsers", type="string"),
     *       @OA\Property(property="additionalUser", type="string"),
     *     ),
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
            'infrastructureName' => 'required',
            'access' => 'required|boolean',
            'includedUsers' => 'required',
            'additionalUser' => 'nullable',
            'category' => 'required',
            'price' => 'required',
        ]);

        $data = [
            'name' => $request['infrastructureName'],
            'is_access' => $request['access'] ? 1 : 0,
            'user_included' => $request['includedUsers'],
            'additional_user_cost' => $request['additionalUser'],
            'category' => $request['category'],
            'price' => $request['price'],
        ];
        SlaInfrastructure::where('id', $id)->update($data);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'SLA Infrastructure'])], 200);
    }
    /**
     * @OA\Delete(
     *      path="/sla-infrastructure/{id}",
     *      operationId="DeleteSingleSlaInfrasturcture",
     *      tags={"Sla Infrastructure"},
     *      summary="Delete single record of Sla infrasturcture",
     *      description="Delete the specified resource",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of sla infrasturcture record",
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
        $model = SlaInfrastructure::find($id);
        $model->delete();
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'SLA Infrastructure'])], 200);
    }
}
