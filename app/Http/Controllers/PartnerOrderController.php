<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Http\Requests\PartnerOrderRequest;
use App\Http\Resources\PartnerManagement\PartnerOrderResource;
use App\Models\PartnerOrder;
use App\Services\PartnerManagement\PartnerOrderService;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as staticRequest;

class PartnerOrderController extends Controller
{
    use CustomHelper;

    protected $partnerOrderService;

    public function __construct(PartnerOrderService $partnerOrderService)
    {
        $this->middleware('check.permission:partner-order.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:partner-order.create', ['only' => ['store']]);
        $this->middleware('check.permission:partner-order.edit', ['only' => ['update']]);
        $this->middleware('check.permission:partner-order.delete', ['only' => ['destroy']]);
        $this->partnerOrderService = $partnerOrderService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $per_page = $request->perPage ?? 25;
        $model = new PartnerOrder();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        if ($request->partnerId) {
            $model = $model->where('partner_id', $request->partnerId);
        }

        if ($request->receiverId) {
            $model = $model->where('receiver_id', $request->receiverId);
        }

        $model = $model->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(function($order) {
                return new PartnerOrderResource($order);
            });
        return response()->json(['partnerOrder' => $model]);
    }

    /**
     * Stores the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PartnerOrderRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $order = $this->partnerOrderService->create($request->prepareRequest());
                $this->partnerOrderService->saveProducts($request->products, $order);
                //            $content = [
                //                'moduleAction' => 'createPartnerOrder',
                //                'data' => [
                //                    'partnerOrder' => $order?->toArray(),
                //                    'products' => $order?->orderProducts?->toArray()
                //                ],
                //            ];
                //            GlobalSettingHelper::sendEloAPIRequest($content, $order);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 422);
        }
        return response()->json(['message' => 'Record saved successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $partnerOrder = PartnerOrder::with('orderProducts')->find($id);
        if ($partnerOrder) {
            $request['show'] = true;
            return response()->json(['data' => new PartnerOrderResource($partnerOrder)]);
        }
        return response()->json(['message' => 'Invalid id provided'], 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PartnerOrderRequest $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $order = $this->partnerOrderService->update($request->prepareRequest(), $id);
                $this->partnerOrderService->saveProducts($request->products, $order);
                //            $content = [
                //                'moduleAction' => 'updatePartnerOrder',
                //                'data' => [
                //                    'partnerOrder' => $order?->toArray(),
                //                    'products' => $order?->orderProducts?->toArray()
                //                ],
                //            ];
                //            GlobalSettingHelper::sendEloAPIRequest($content, $order);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 422);
        }
        return response()->json(['message' => 'Record updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $partnerOrder = PartnerOrder::find($id);
        if ($partnerOrder) {
            $partnerOrder->delete();
            $partnerOrder->deleted_at = Carbon::now()->toIso8601ZuluString();
//            $content = [
//                'moduleAction' => 'deletePartnerOrder',
//                'data' => [
//                    'partnerOrder' => $partnerOrder->toArray(),
//                    'products' => $partnerOrder?->orderProducts?->toArray()
//                ]
//            ];
//            GlobalSettingHelper::sendEloAPIRequest($content);
            return response()->json(['message' => 'Record deleted successfully']);
        }
        return response()->json(['message' => 'Invalid id provided'], 400);
    }
}
