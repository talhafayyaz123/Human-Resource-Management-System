<?php

namespace App\Http\Controllers;

use App\Services\OfferRequestService;
use App\Http\Requests\OfferRequestRequest;
use App\Http\Resources\OfferRequestResource;
use App\Models\OfferRequest;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OfferRequestController extends Controller
{
    use CustomHelper;
    public OfferRequestService $offerRequestService;

    public function __construct(OfferRequestService $offerRequestService)
    {
        $this->middleware('check.permission:offer-requests.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:offer-requests.create', ['only' => ['store']]);
        $this->middleware('check.permission:offer-requests.edit', ['only' => ['update']]);
        $this->middleware('check.permission:offer-requests.delete', ['only' => ['destroy']]);
        $this->offerRequestService = $offerRequestService;
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
        $per_page = $request->perPage ?? 10;
        $model = new OfferRequest();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        // Paginate the FleetCar records
        $offer_requests = $model->paginate($per_page);
        // Return the paginated FleetCarResource
        return response()->json([
            'data' => OfferRequestResource::collection($offer_requests),
            'links' => $offer_requests->links(),
            'current_page' => $offer_requests->currentPage(),
            'from' => $offer_requests->firstItem(),
            'last_page' => $offer_requests->lastPage(),
            'path' => request()->url(),
            'per_page' => $offer_requests->perPage(),
            'to' => $offer_requests->lastItem(),
            'total' => $offer_requests->total(),
        ]);
    }
    /**
     * Stores the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(OfferRequestRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        try {
            DB::transaction(function () use ($data) {
                $this->offerRequestService->createOfferRequest($data);
            });
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ], 500);
        }
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Offer Request'])], 200);
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
        $offer_request = OfferRequest::findOrFail($id);

        // Return a single FleetCarResource for the found record
        return new OfferRequestResource($offer_request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(OfferRequestRequest $request, $id)
    {
        $validated_data = $request->validated();
        $offer_request = OfferRequest::findOrFail($id);
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        try {
            DB::transaction(function () use ($offer_request, $data) {
                $this->offerRequestService->updateOfferRequest($offer_request, $data);
            });
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ], 500);
        }
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Offer Request'])], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $offer_request = OfferRequest::findOrFail($id);
        $this->offerRequestService->deleteOfferRequest($offer_request);
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Offer Request'])], 200);
    }

    /**
     * Create a new offer based on offer request.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createOffer($id)
    {
        $offer_request = OfferRequest::findOrFail($id);
        return $this->offerRequestService->createOffer($offer_request);
    }
}
