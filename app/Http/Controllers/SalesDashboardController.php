<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleOffer;
use App\Models\Company;
use Carbon\Carbon;
use App\Traits\CustomHelper;

class SalesDashboardController extends Controller
{
    use CustomHelper;

    /**
     * calculates monthly statistics for offers and orders
     * @param Request $request
     * @return JSONResponse
     */
    public function offersStatistics(Request $request)
    {
        $date = Carbon::now();

        // if date is present of the request than set $date to that date
        if ($request->date) {
            $date = Carbon::parse($request->date);
        }


        return response()->json([
            // filter the data based on type
            'data' => $request->type == 'monthly' ? self::offersStatisticsMonthly($date, $request->companyId) : self::offersStatisticsYearly($date, $request->companyId)
        ]);
    }

    /**
     * computes statistics by month
     * @param date $date
     * @return array
     */
    private static function offersStatisticsMonthly($date, $companyId = null)
    {
        // contains the time tracker data for each day of the month from the $date
        $month_data_by_day = [];

        // the first day of the month from $date
        $start_of_month = clone $date->startOfMonth();


        $netto_total_offers_sum = 0; // netto total of offers
        $netto_total_orders_sum = 0; // netto total of orders

        // loop from the first day of the month to the last
        for ($i = 1; $i <= $date->lastOfMonth()->day; $i++) {

            $netto_total_offers = []; // netto total of offers
            $netto_total_orders = []; // netto total of orders

            // sum the netto total of sale_offer_components for offer_type 'offer'
            $sale_offers = SaleOffer::where('sale_offers.type', 'offer')->where('sale_offers.remove_from_statistics', 0)->whereDate('sale_offers.created_at', $start_of_month);
            if ($companyId) {
                $sale_offers->where('company_id', $companyId);
            }
            $sale_offers = $sale_offers->get();
            foreach ($sale_offers as $offer) {
                foreach ($offer->components as $component) {
                    if (isset($netto_total_offers[$component->type]) == false) {
                        $netto_total_offers[$component->type] = 0;
                    }

                    $netto_total_offers[$component->type] += $component->total_netto;
                    $netto_total_offers_sum += $component->total_netto;
                }
            }

            // sum the netto total of sale_offer_components for offer_type 'order'
            $sale_orders = SaleOffer::where('sale_offers.type', 'order')->where('sale_offers.remove_from_statistics', 0)->whereDate('sale_offers.created_at', $start_of_month);
            if ($companyId) {
                $sale_orders->where('company_id', $companyId);
            }
            $sale_orders = $sale_orders->get();

            foreach ($sale_orders as $order) {
                foreach ($order->components as $component) {
                    if (isset($netto_total_orders[$component->type]) == false) {
                        $netto_total_orders[$component->type] = 0;
                    }

                    $netto_total_orders[$component->type] += $component->total_netto;
                    $netto_total_orders_sum += $component->total_netto;
                }
            }

            // set the month_data_by_day for the day
            $month_data_by_day[$start_of_month->toDateString()] = [
                'nettoTotalOffers' => $netto_total_offers_sum,
                'nettoTotalOrders' => $netto_total_orders_sum,
                'nettoTotalOffersDetails' => $netto_total_offers,
                'nettoTotalOrdersDetails' => $netto_total_orders
            ];
            // go to the next day
            $start_of_month = $start_of_month->addDay();
        }

        return $month_data_by_day;
    }

    /**
     * computes statistics by year
     * @param date $date
     * @return array
     */
    private static function offersStatisticsYearly($date, $companyId = null)
    {
        $months_data = [];


        $netto_total_offers_sum = 0; // netto total of offers
        $netto_total_orders_sum = 0; // netto total of orders

        // loop from the first day of the month to the last
        for ($i = 1; $i <= 12; $i++) {

            $netto_total_offers = []; // netto total of offers
            $netto_total_orders = []; // netto total of orders
            // sum the netto total of sale_offer_components for offer_type 'offer'
            $sale_offers = SaleOffer::where('sale_offers.type', 'offer')->where('sale_offers.remove_from_statistics', 0)->whereYear('sale_offers.created_at', $date->year)->whereMonth('sale_offers.created_at', $i);
            if ($companyId) {
                $sale_offers->where('company_id', $companyId);
            }
            $sale_offers = $sale_offers->get();
            foreach ($sale_offers as $offer) {
                foreach ($offer->components as $component) {
                    if (isset($netto_total_offers[$component->type]) == false) {
                        $netto_total_offers[$component->type] = 0;
                    }

                    $netto_total_offers[$component->type] += $component->total_netto;
                    $netto_total_offers_sum += $component->total_netto;
                }
            }

            // sum the netto total of sale_offer_components for offer_type 'order'
            $sale_orders = SaleOffer::where('sale_offers.type', 'order')->where('sale_offers.remove_from_statistics', 0)->whereYear('sale_offers.created_at', $date->year)->whereMonth('sale_offers.created_at', $i);
            if ($companyId) {
                $sale_orders->where('company_id', $companyId);
            }
            $sale_orders = $sale_orders->get();
            foreach ($sale_orders as $order) {
                foreach ($order->components as $component) {
                    if (isset($netto_total_orders[$component->type]) == false) {
                        $netto_total_orders[$component->type] = 0;
                    }

                    $netto_total_orders[$component->type] += $component->total_netto;
                    $netto_total_orders_sum += $component->total_netto;
                }
            }


            $months_data[$i] = [
                'nettoTotalOffers' => $netto_total_offers_sum,
                'nettoTotalOrders' => $netto_total_orders_sum,
                'nettoTotalOffersDetails' => $netto_total_offers,
                'nettoTotalOrdersDetails' => $netto_total_orders
            ];

        }

        return $months_data;
    }

    /**
     * calculates product statistics based on type for offer and order
     * @param Request $request
     * @return JSONResponse
     */
    public function productProportionsStatistics(Request $request)
    {
        $date = Carbon::now();

        // if date is present of the request than set $date to that date
        if ($request->date) {
            $date = Carbon::parse($request->date);
        }

        $sale_offers = SaleOffer::where('sale_offers.type', 'offer')->where('sale_offers.remove_from_statistics', 0);
        $sale_orders = SaleOffer::where('sale_offers.type', 'order')->where('sale_offers.remove_from_statistics', 0);

        // filter the data based on type
        if ($request->type == 'monthly') {
            $sale_offers = $sale_offers->whereMonth('sale_offers.created_at', '=', $date->month);
            $sale_orders = $sale_orders->whereMonth('sale_offers.created_at', '=', $date->month);
        } else {
            $sale_offers = $sale_offers->whereYear('sale_offers.created_at', '=', $date->year);
            $sale_orders = $sale_orders->whereYear('sale_offers.created_at', '=', $date->year);
        }

        // filter the records on the bases of companyId
        if ($request->companyId) {
            $sale_offers = $sale_offers->where('sale_offers.company_id', $request->companyId);
            $sale_orders = $sale_orders->where('sale_offers.company_id', $request->companyId);
        }

        // groups by offer types and count of sale_offer_components with that offer type (offer_type == 'offer')
        $offer_proportions = $sale_offers->join('sale_offer_components', 'sale_offers.id', '=', 'sale_offer_components.sale_offer_id')
            ->selectRaw('sale_offer_components.type, SUM(sale_offer_components.total_netto) AS total')->groupBy('sale_offer_components.type')->get();
        // groups by offer types and count of sale_offer_components with that offer type (offer_type == 'order')
        $order_proportions = $sale_orders->join('sale_offer_components', 'sale_offers.id', '=', 'sale_offer_components.sale_offer_id')
            ->selectRaw('sale_offer_components.type, SUM(sale_offer_components.total_netto) AS total')->groupBy('sale_offer_components.type')->get();

        return response()->json([
            'offers' => $offer_proportions,
            'orders' => $order_proportions
        ]);
    }

    /**
     * calculates lead statistics based on contact report sources
     * @param Request $request
     * @return JSONResponse
     */
    public function leadChannelsStatistics(Request $request)
    {
        $date = Carbon::now();

        // if date is present of the request than set $date to that date
        if ($request->date) {
            $date = Carbon::parse($request->date);
        }

        $company = Company::where('customer_type', 'lead');

        // filter the records on the bases of companyId
        if ($request->companyId) {
            $company->where('id', $request->companyId);
        }

        // filter the data based on type
        if ($request->type == "monthly") {
            $company = $company->whereMonth('companies.created_at', '=', $date->month);
        } else {
            $company = $company->whereYear('companies.created_at', '=', $date->year);
        }

        // group by contact report sources and count of leads with that particular contact report source
        $lead_sources = $company->join('lead_contact_report_source', 'companies.id', '=', 'lead_contact_report_source.lead_id')
            ->join('contact_report_sources', 'lead_contact_report_source.contact_report_source_id', '=', 'contact_report_sources.id')
            ->selectRaw('contact_report_sources.name, COUNT(lead_contact_report_source.lead_id) AS total')
            ->groupBy('contact_report_sources.name')->get();

        return response()->json([
            'data' => $lead_sources
        ]);
    }

    public function offersData(Request $request)
    {
        $per_page = $request->perPage ?? 10;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $offerType = $request->offerType ?? 'offer';
        // if date is present of the request than set $date to that date
        if ($request->date) {
            $date = Carbon::parse($request->date);
        } else {
            $date = Carbon::now();
        }

        $saleOffers = new SaleOffer();

        if ($request->companyId){
            $saleOffers = $saleOffers->where('company_id', $request->companyId);
        }

        if ($request->type == 'monthly') {
            $saleOffers = $saleOffers->where('type', $offerType)->where('remove_from_statistics', 0)->whereMonth('created_at', $date->month)->paginate($per_page);
        } else {
            $saleOffers = $saleOffers->where('type', $offerType)->where('remove_from_statistics', 0)->whereYear('created_at', $date->year)->paginate($per_page);
        }

        $model_collect = $saleOffers->map(function ($item) use ($offerType) {
            $returnData = [
                'id' => $item->id,
                'offerNumber' => $item->sale_offer_number,
                'company' => $item->company->company_name,
                'terms' => $item->term->name,
                'receiverType' => $item->receiver_type,
                'project' => $item->project?->name,
                'type' => $item->offer_type,
                'offerStatus' => $item->offer_status,
                'totalNetto' => floatval($item->netto_total),
                'identifier' => $item->identifier,
                'companyDetails' => $item->company ? [
                    'id' => $item->company?->id,
                    'companyName' => $item->company?->company_name,
                    'vatId' => $item->company?->vat_id,
                    'url' => $item->company?->url,
                    'type' => $item->company?->type,
                    'customerType' => $item->company?->customer_type,
                    'companyNumber' => $item->company?->company_number,
                    'state' => $item->company?->headQuarters?->state,
                    'city' => $item->company?->headQuarters?->city,
                    'country' => $item->company?->headQuarters?->country,
                    'address' => $item->company?->headQuarters?->address_first,
                    'code' => $item->company?->headQuarters?->zip,
                    'notes' => $item->company?->notes,
                    'status' => $item->company?->status,
                    'expiryDate' => $item->company?->expiry_dt ? Carbon::parse($item->company?->expiry_dt)->toDateString() : '',
                    'termsOfPayment' => $item->company?->terms_of_payment,
                ] : null,
            ];
            if ($offerType == 'order') {
                $returnData['orderStatus'] = $item->order_status;
            } else {
                $returnData['offerStatus'] = $item->offer_status;
            }
            return $returnData;
        });
        if ($sort_by && $sort_order) {
            $model_collect = self::applySorting($model_collect, $sort_by, $sort_order);
        }
        return response()->json([
            'data' => $model_collect, 'links' => $saleOffers->links(),
            'meta' => [
                'current_page' => $saleOffers->currentPage(),
                'from' => $saleOffers->firstItem(),
                'last_page' => $saleOffers->lastPage(),
                'path' => request()->url(),
                'per_page' => $saleOffers->perPage(),
                'to' => $saleOffers->lastItem(),
                'total' => $saleOffers->total(),
            ],
        ], 200);
    }

    private static function applySorting($collection, $sortBy, $sortOrder)
    {
        return $collection->sortBy($sortBy, SORT_REGULAR, $sortOrder == 'asc' ? false : true)->values();
    }
}
