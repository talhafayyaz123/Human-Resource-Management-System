<?php

namespace App\Http\Controllers;

use App\Models\SlaLevelChange;
use Illuminate\Http\Request;

class SlaLevelChangeController extends Controller
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
     *      path="/sla-level",
     *      operationId="getSlaLevel",
     *      tags={"Sla Level"},
     *      summary="Get list of Sla Level",
     *      description="Returns list of sla levels",
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
        $slaLevelChanges = SlaLevelChange::get();
        return response()->json([
            'data' => $slaLevelChanges->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'priority' => $item->priority,
                    'change' => $item->change,
                    'factor' => $item->factor,
                ];
            })
        ]);
    }
    /**
     * @OA\Post(
     *      path="/sla-level",
     *      operationId="StoreSlaLevel",
     *      tags={"Sla Level"},
     *      summary="Store Sla Level record",
     *      description="Store a newly created resource in storage",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Json request",
     *    @OA\JsonContent(
     *       required={"slaName","levels"},
     *       @OA\Property(property="slaName", type="string"),
     *       @OA\Property(property="levels", type="array",
     *          @OA\Items(
     *     type="object",
     *     required={"priority", "incident"},
     *                     @OA\Property(property="priority", type="string" ),
     *                     @OA\Property(property="incident", type="string" ),
     *                 )
     *      ),
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
            'name' => 'required',
            "priority" => 'required',
            "change" => 'required',
            "factor" => 'nullable',
        ]);
        $slaLevelChange = new SlaLevelChange();
        $slaLevelChange->name = $request['name'];
        $slaLevelChange->priority = $request['priority'];
        $slaLevelChange->change = $request['change'];
        $slaLevelChange->factor = $request['factor'];
        $slaLevelChange->save();
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'SLA Level Change'])], 200);
    }
    /**
     * @OA\Get(
     *      path="/sla-level/{id}",
     *      operationId="getSingleSlaLevel",
     *      tags={"Sla Level"},
     *      summary="Get single record of Sla Level",
     *      description="Display the specified resource",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of sla Level record",
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
        $slaLevelChange = SlaLevelChange::findOrFail($id);
        return response()->json(['data' => [
            'id' => $slaLevelChange->id,
            'name' => $slaLevelChange->name,
            'priority' => $slaLevelChange->priority,
            'change' => $slaLevelChange->change,
            'factor' => $slaLevelChange->factor,
        ]]);
    }
    /**
     * @OA\Put(
     *      path="/sla-level/{id}",
     *      operationId="updateSlaLevel",
     *      tags={"Sla Level"},
     *      summary="Update Sla Level record",
     *      description="Update a resource in storage",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of sla Level record",
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
     *       required={"slaName","levels"},
     *       @OA\Property(property="slaName", type="string"),
     *       @OA\Property(property="levels", type="array",
     *          @OA\Items(
     *     type="object",
     *     required={"priority", "incident"},
     *                     @OA\Property(property="priority", type="string" ),
     *                     @OA\Property(property="incident", type="string" ),
     *                 )
     *      ),
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
            'name' => 'required',
            "priority" => 'required',
            "change" => 'required',
            "factor" => 'nullable',
        ]);
        $slaLevelChange = SlaLevelChange::find($id);
        $slaLevelChange->name = $request['name'];
        $slaLevelChange->priority = $request['priority'];
        $slaLevelChange->change = $request['change'];
        $slaLevelChange->factor = $request['factor'];
        $slaLevelChange->save();
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'SLA Level Change'])], 200);
    }
    /**
     * @OA\Delete(
     *      path="/sla-level/{id}",
     *      operationId="DeleteSingleSlaLevel",
     *      tags={"Sla Level"},
     *      summary="Delete single record of Sla Level",
     *      description="Delete the specified resource",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of sla Level record",
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
        $model = SlaLevelChange::find($id);
        $model->delete();
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'SLA Level Change'])], 200);
    }
}
