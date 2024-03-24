<?php

namespace App\Http\Controllers;

use App\Models\CancelationRequest;
use Illuminate\Http\Request;
use App\Services\CancelationRequestService;
use App\Traits\CustomHelper;
use App\Http\Resources\ProductStoreCancelRequestResource;
use App\Http\Requests\CancelProductStoreRequest;
use Illuminate\Support\Facades\Request as staticRequest;

class CancelationRequestController extends Controller
{
    use CustomHelper;
    public CancelationRequestService $cancelationRequestService;

    public function __construct(CancelationRequestService $cancelationRequestService)
    {
        $this->middleware('check.permission:cancelation-request-service.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:cancelation-request-service.create', ['only' => ['store']]);
        $this->middleware('check.permission:cancelation-request-service.edit', ['only' => ['update']]);
        $this->middleware('check.permission:cancelation-request-service.delete', ['only' => ['destroy']]);
        $this->cancelationRequestService = $cancelationRequestService;
    }
   
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->perPage ?? 25;

        $sortBy = $request->get('sortBy');
        $sortOrder = $request->get('sortOrder');
        $model = new CancelationRequest();
        if ($sortBy && $sortOrder) {
            $model = $this->applySortingBeforePagination($model, $sortBy, $sortOrder);
        }

        $store = $model->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn ($store) => [
                'id' => $store->id,
                'customer' =>  $store->customer?->company_name,
                'reason' => $store->reason,
                'partner' => $store->partner?->company_name,
                'entries' => $store->entries->pluck('product_title'),
            ]);
        return response()->json(['CancelRequests' => $store]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CancelProductStoreRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
      
        $requests = $this->cancelationRequestService->create($data);
        if ($requests) {
            return response()->json([
                'message' => 'Record created successfully.',
                'cancelRequestId' => $requests['id'],
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong',
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CancelationRequest  $cancelationRequest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productStore = CancelationRequest::find($id);
        if ($productStore) {
            return response()->json([
                'data' => new ProductStoreCancelRequestResource($productStore)
            ]);
        }
        return response()->json([
            'message' => 'Invalid id.'
        ], 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CancelationRequest  $cancelationRequest
     * @return \Illuminate\Http\Response
     */
    public function update(CancelProductStoreRequest $request, $id)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        unset($data['store_entry_id']);
      
        $productStore = CancelationRequest::updateOrCreate(['id' => $id], $data);
        if ($productStore) {
            if ($request->storeEntryId && count($request->storeEntryId) > 0) {
                $productStore->entries()->sync($request->storeEntryId);
            }

            return response()->json([
                'message' => 'Record update successfully.',
                'productStoreCancelRequestId' => $productStore->id,
            ]);
        }
        return response()->json([
            'message' => 'Something went wrong',
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CancelationRequest  $cancelationRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cancelationRequest = CancelationRequest::find($id);
        $cancelationRequest->entries()->detach();
        $cancelationRequest->delete();

            return response()->json([
                'message' => 'Record deleted successfully.'
            ]);
        
        return response()->json([
            'message' => 'Invalid id.'
        ], 400);
    }
}
