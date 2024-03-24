<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditorInvoiceRequest;
use App\Http\Resources\CreditorInvoiceResource;
use App\Models\CreditorInvoice;
use App\Services\CreditorInvoiceService;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;


class CreditorInvoiceController extends Controller
{
    use CustomHelper;
    public CreditorInvoiceService $creditorInvoiceService;

    public function __construct(CreditorInvoiceService $creditorInvoiceService)
    {
        $this->middleware('check.permission:creditor-invoices.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:creditor-invoices.create', ['only' => ['store']]);
        $this->middleware('check.permission:creditor-invoices.edit', ['only' => ['update']]);
        $this->middleware('check.permission:creditor-invoices.delete', ['only' => ['destroy']]);
        $this->creditorInvoiceService = $creditorInvoiceService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new CreditorInvoice();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        // Paginate the FleetCar records
        $creditor_invoices = $model->filter(staticRequest::only('search', 'type', 'status'))
            ->paginate($per_page);
        // Return the paginated FleetCarResource
        return response()->json([
            'data' => CreditorInvoiceResource::collection($creditor_invoices),
            'links' => $creditor_invoices->links(),
            'current_page' => $creditor_invoices->currentPage(),
            'from' => $creditor_invoices->firstItem(),
            'last_page' => $creditor_invoices->lastPage(),
            'path' => request()->url(),
            'per_page' => $creditor_invoices->perPage(),
            'to' => $creditor_invoices->lastItem(),
            'total' => $creditor_invoices->total(),
        ]);
    }
    /**
     * Stores the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(CreditorInvoiceRequest $request)
    {
        $validated_data = $request->validated();
        $this->creditorInvoiceService->createCreditorInvoice($validated_data);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Creditor Invoice'])], 200);
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
        $creditor_invoice = CreditorInvoice::findOrFail($id);

        // Return a single FleetCarResource for the found record
        return new CreditorInvoiceResource($creditor_invoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(CreditorInvoiceRequest $request, $id)
    {
        $validated_data = $request->validated();
        $creditor_invoice = CreditorInvoice::findOrFail($id);
        $this->creditorInvoiceService->updateCreditorInvoice($creditor_invoice,  $validated_data);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Creditor Invoice'])], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $creditor_invoice = CreditorInvoice::findOrFail($id);
        $this->creditorInvoiceService->deleteCreditorInvoice($creditor_invoice);
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Creditor Invoice'])], 200);
    }
}
