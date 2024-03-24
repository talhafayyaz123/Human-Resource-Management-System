<?php

namespace App\Http\Controllers;

use App\Models\SlaServiceTime;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;

class SlaServiceTimeController extends Controller
{
    use CustomHelper;
    public function __construct()
    {
        $this->middleware('check.permission:sla.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:sla.create', ['only' => ['store']]);
        $this->middleware('check.permission:sla.edit', ['only' => ['update']]);
        $this->middleware('check.permission:sla.delete', ['only' => ['destroy']]);
    }

    /**
     * @OA\Get(
     *      path="/sla-service-time",
     *      operationId="getSlaServiceTime",
     *      tags={"Sla Service Time"},
     *      summary="Get list of Sla Service Time",
     *      description="Returns list of sla Service Time",
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
        if ($request->perPage) {

            $per_page = $request->perPage ?? 25;
            $sort_by = $request->get('sortBy');
            $sort_order = $request->get('sortOrder');
            $model = new SlaServiceTime;
            if ($sort_by && $sort_order) {
                $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
            }
            $model = $model->paginate($per_page)
                ->withQueryString();
           return response()->json([
                'data' => $model->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'days' => $item->days,
                        'from' => $item->from,
                        'to' => $item->to,
                        'factor' => $item->factor,
                    ];
                })
            ]);
        }else{
            $slaServiceTime = SlaServiceTime::all();
            return response()->json([
                'data' => $slaServiceTime->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'days' => $item->days,
                        'from' => $item->from,
                        'to' => $item->to,
                        'factor' => $item->factor,
                    ];
                })
            ]);
        }
    }
    /**
     * @OA\Post(
     *      path="/sla-service-time",
     *      operationId="StoreSlaServiceTime",
     *      tags={"Sla Service Time"},
     *      summary="Store Sla Service Time record",
     *      description="Store a newly created resource in storage",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Json request",
     *    @OA\JsonContent(
     *       required={"serviceName","days", "from", "to"},
     *       @OA\Property(property="serviceName", type="string"),
     *       @OA\Property(property="days", type="boolean"),
     *      @OA\Property(property="from", type="string"),
     *       @OA\Property(property="to", type="string"),
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
            'serviceName' => 'required',
            'days' => 'required',
            'from' => 'required',
            'to' => 'required',
            'factor' => 'required'
        ]);

        $data = [
            'name' => $request['serviceName'],
            'from' => $request['from'],
            'to' => $request['to'],
            'days' => $request['days'],
            'factor' => $request['factor'],
        ];
        SlaServiceTime::create($data);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'SLA Service Time'])], 200);
    }
    /**
     * @OA\Get(
     *      path="/sla-service-time/{id}",
     *      operationId="getSingleSlaServiceTime",
     *      tags={"Sla Service Time"},
     *      summary="Get single record of Sla Service Time",
     *      description="Display the specified resource",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of sla service time record",
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
        $slaServiceTime = SlaServiceTime::find($id);
        return response()->json(['data' => [
            'id' => $slaServiceTime->id,
            'name' => $slaServiceTime->name,
            'days' => $slaServiceTime->days,
            'from' => $slaServiceTime->from,
            'to' => $slaServiceTime->to,
            'factor' => $slaServiceTime->factor,
        ]]);
    }
    /**
     * @OA\Put(
     *      path="/sla-service-time/{id}",
     *      operationId="updateSlaServiceTime",
     *      tags={"Sla Service Time"},
     *      summary="Update Sla Service Time record",
     *      description="update a resource in storage",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of sla service time record",
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
     *       required={"serviceName","days", "from", "to"},
     *       @OA\Property(property="serviceName", type="string"),
     *       @OA\Property(property="days", type="boolean"),
     *      @OA\Property(property="from", type="string"),
     *       @OA\Property(property="to", type="string"),
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
            'serviceName' => 'required',
            'days' => 'required',
            'from' => 'required',
            'to' => 'required',
            'factor' => 'required',
        ]);

        $data = [
            'name' => $request['serviceName'],
            'from' => $request['from'],
            'to' => $request['to'],
            'days' => $request['days'],
            'factor' => $request['factor'],
        ];
        SlaServiceTime::where('id', $id)->update($data);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'SLA Service Time'])], 200);
    }
    /**
     * @OA\Delete(
     *      path="/sla-service-time/{id}",
     *      operationId="DeleteSingleSlaServiceTime",
     *      tags={"Sla Service Time"},
     *      summary="Delete single record of Sla Service tim",
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
        $model = SlaServiceTime::find($id);
        $model->delete();
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'SLA Service Time'])], 200);
    }
}
