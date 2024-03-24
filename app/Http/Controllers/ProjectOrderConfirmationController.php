<?php

namespace App\Http\Controllers;

use App\Models\SaleOffer;
use Illuminate\Http\Request;

class ProjectOrderConfirmationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $company_id)
    {
        $sale_offers_with_company = SaleOffer::where('company_id', $company_id)->where('type', 'order')->get();
        $sale_offer_company_collections = $sale_offers_with_company->map(function ($sale_offer) {
            return [
                'id' => $sale_offer->id,
                'quantity' => $sale_offer->components()->where('type', 'service')->sum('quantity'), // sum all the quantities of service components linked to the $sale_offer
                'saleOfferNumber' => $sale_offer->sale_offer_number
            ];
        });
        return response()->json(['data' => $sale_offer_company_collections]);
    }
}
