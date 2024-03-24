<?php

namespace App\Http\Controllers;

use App\Helpers\OrderAndOfferHelper;
use App\Models\SaleOffer;
use Illuminate\Http\Request;
use App\Models\GlobalSetting;

class OrderConfirmationController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:order-confirmation.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:order-confirmation.create', ['only' => ['store']]);
        $this->middleware('check.permission:order-confirmation.edit', ['only' => ['update']]);
        $this->middleware('check.permission:order-confirmation.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $request->request->add(['offerType' => 'order']);
        return OrderAndOfferHelper::index($request);
    }

    public function store(Request $request)
    {
        $request->validate([
            "companyId" => "required",
            "termId" => "required",
            "type" => "required",
            "expiryDate" => "required",
            'orderStatus' => 'required',
            'offerId' => 'nullable|int',
        ]);
        $request->request->add(['offerType' => 'order']);
        return OrderAndOfferHelper::store($request);
    }

    public function show($id)
    {
        return OrderAndOfferHelper::show($id);
    }

    public function offerConfirmationForDocumentGeneration($id)
    {
        $offerTemplateId = GlobalSetting::where("key", 'orderConfirmationTemplateId')?->first()?->value;

        if (!$offerTemplateId) {
            return response()->json([
                "message" => "Please assign a template document for offer confirmation module"
            ], 422);
        }
        return [
            'offer' => OrderAndOfferHelper::offerForDocumentGeneration($id),
            "offerTemplateId" => $offerTemplateId,
        ];
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "companyId" => "required",
            "termId" => "required",
            "type" => "required",
            "expiryDate" => "required",
            'orderStatus' => 'required',
            'offerId' => 'nullable|int',
        ]);
        $request->request->add(['offerType' => 'order']);
        return OrderAndOfferHelper::update($request, $id);
    }

    public function destroy($id)
    {
        return OrderAndOfferHelper::destroy($id);
    }

    public function createInvoices($id)
    {
        return OrderAndOfferHelper::createInvoices($id);
    }

    /**
     * updates the order status
     * @param integer $id
     * @param Request $request
     * @return JSONResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            "status" => "required|in:sent"
        ]);
        $offer = SaleOffer::findOrFail($id);
        $offer->order_status = $request->status;
        $offer->save();
        return response()->json([
            "success" => true,
            "message" => "Offer confirmation status has been updated."
        ]);
    }

}
