<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Http\Requests\OutboundedContractRequest;
use App\Http\Resources\OutboundedContractResource;
use App\Models\OutboundedContract;
use App\Models\ContractField;
use App\Models\ContractAttachment;

use App\Services\OutboundedContractService;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;


class OutboundedContractController extends Controller
{
    use CustomHelper;
    public OutboundedContractService $outboundedContractService;

    public function __construct(OutboundedContractService $outboundedContractService)
    {
        $this->middleware('check.permission:outbounded-contracts.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:outbounded-contracts.create', ['only' => ['store']]);
        $this->middleware('check.permission:outbounded-contracts.edit', ['only' => ['update']]);
        $this->middleware('check.permission:outbounded-contracts.delete', ['only' => ['destroy']]);
        $this->outboundedContractService = $outboundedContractService;
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
        $model = new OutboundedContract();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        //for customer portal
        if ($request->customerId) {
            $model = $model->where('receiver_id', $request->customerId);
        }

        if ($request->status) {
            $model = $model->where('status', $request->status);
        }

        // Paginate the FleetCar records
        $outbounded_contract = $model->paginate(10);
        // Return the paginated FleetCarResource
        return response()->json([
            'data' => OutboundedContractResource::collection($outbounded_contract),
            'links' => $outbounded_contract->links(),
            'current_page' => $outbounded_contract->currentPage(),
            'from' => $outbounded_contract->firstItem(),
            'last_page' => $outbounded_contract->lastPage(),
            'path' => request()->url(),
            'per_page' => $outbounded_contract->perPage(),
            'to' => $outbounded_contract->lastItem(),
            'total' => $outbounded_contract->total(),
        ]);
    }
    /**
     * Stores the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(OutboundedContractRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        try {
            DB::transaction(function () use ($data, $request ) {
                $userId = $this->getAuthUserId($request);
                $model = $this->outboundedContractService->createOutboundedContract($data, $userId);
                $content = [
                    'moduleAction' => 'createOutboundedContract',
                    'data' => [
                        'supplier' => $model?->toArray(),

                    ],
                ];
                GlobalSettingHelper::sendEloAPIRequest($content, $model);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 422);
        }
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Outbounded Contract'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find the FleetCar record by ID
        $outbounded_contract = OutboundedContract::findOrFail($id);

        // Return a single FleetCarResource for the found record
        return new OutboundedContractResource($outbounded_contract);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(OutboundedContractRequest $request, $id)
    {
        $validated_data = $request->validated();
        $outbounded_contract = OutboundedContract::findOrFail($id);
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        try {
            DB::transaction(function () use ($data, $outbounded_contract, $request) {
                $userId = $this->getAuthUserId($request);
                $model =  $this->outboundedContractService->updateOutboundedContract($outbounded_contract,  $data, $userId);
                $content = [
                    'moduleAction' => 'createOutboundedContract',
                    'data' => [
                        'supplier' => $model?->toArray(),

                    ],
                ];
                GlobalSettingHelper::sendEloAPIRequest($content, $model);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 422);
        }
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Outbounded Contract']), 'id' => $outbounded_contract->id], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $outbounded_contract = OutboundedContract::findOrFail($id);
        $this->outboundedContractService->deleteOutboundedContract($outbounded_contract);
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Outbounded Contract'])], 200);
    }

    public function createInvoice(Request $request, int $id)
    {
        $outbounded_contract = OutboundedContract::findOrFail($id);
        try {
            $this->outboundedContractService->createInvoice($outbounded_contract, $request);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ], 500);
        }
        return response()->json(['message' => 'Invoice created successfully'], 200);
    }

    public function createInvoiceTemplate(Request $request, int $id)
    {
        $outbounded_contract = OutboundedContract::findOrFail($id);
        try {
            $this->outboundedContractService->createInvoiceTemplateByContract($outbounded_contract, $request);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ], 500);
        }
        return response()->json(['message' => 'Invoice template created successfully'], 200);
    }

    public function showByCompany(Request $request)
    {
        $model = new OutboundedContract;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->where('receiver_id', $request->id);
        $models = $model->get();
        return response()->json([
            'data' => OutboundedContractResource::collection($models)
        ]);
    }
}
