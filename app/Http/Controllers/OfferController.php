<?php

namespace App\Http\Controllers;

use App\Helpers\OrderAndOfferHelper;
use App\Models\SaleOffer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ExportedCsvInfo;
use App\Models\GlobalSetting;

class OfferController extends Controller
{

    use CustomHelper;
    /**
     *Runs when instance is initiated
     */

    public function __construct()
    {
        $this->middleware('check.permission:offer.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:offer.create', ['only' => ['store']]);
        $this->middleware('check.permission:offer.edit', ['only' => ['update']]);
        $this->middleware('check.permission:offer.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return OrderAndOfferHelper::index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "companyId" => "required",
            "termId" => "required",
            "type" => "required",
            "expiryDate" => "required",
        ]);

        return OrderAndOfferHelper::store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return OrderAndOfferHelper::show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "companyId" => "required",
            "termId" => "required",
            "type" => "required",
            "expiryDate" => "required",
        ]);

        return OrderAndOfferHelper::update($request, $id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return OrderAndOfferHelper::destroy($id);
    }

    /**
     * Get all offers belonging to a company
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOffersByCompany(Request $request)
    {
        $offerType = $request->offerType ?? 'offer';
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new SaleOffer();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->where("sale_offers.company_id", $request->id)->where('sale_offers.type', $offerType)->get();
        $model_collect = $model->map(function ($item) use ($offerType) {
            $returnData = [
                'id' => $item->id,
                'offerType' => $item->type,
                'offerNumber' => $item->sale_offer_number,
                'company' => $item->company->company_name,
                'terms' => $item->term->name,
                'receiverType' => $item->receiver_type,
                'project' => $item->project?->name,
                'type' => $item->offer_type,
                'components' => $item->components->map(function ($component) use ($item) {
                    $productsGroupedByCategory = collect();
                    if ($item->grouped_by == "category" && $item->type == 'service') {
                        $productsGroupedByCategory = Product::with('productCategory')->whereIn('id', $component?->products)->get();
                    }
                    return [
                        'id' => $component->id,
                        'type' => $component->type,
                        'product' => [
                            'id' => $component->product->id,
                            'articleNumber' => $component->product->article_number,
                            'name' => $component->product->name,
                            'listingPrice' => $component->product->listing_price,
                            'status' => $component->product->status ? 'active' : 'deactive',
                            'salePrice' => $component->product->sale_price,
                            'profit' => $component->product->profit,
                            'discount' => $component->product->discount ?? 0,
                            'maintenancePrice' => $component->product->maintanence_price,
                            'maintenanceRate' => $component->product->maintenance_rate,
                            'quantity' => $component->product->quantity,
                            'productSoftware' => $component->product->productSoftware,
                            'tax' => $component->product->tax,
                            'type' => $component->product->payment_details_type,
                            'unit' => $component->product->productUnit,
                            'dailyRate' => $component->product->daily_rate,
                            'serviceDays' => $component->product->service_days,
                            'category' => [
                                "id" => $component->product?->productCategory?->id,
                                "name" => $component->product?->productCategory?->name,
                                "defaultUnit" => $component->product?->productCategory?->default_unit,
                                "isDefaultOnOffers" => $component->product?->productCategory?->is_default_on_offers
                            ]
                        ],
                        'quantity' => $component->quantity,
                        'duration' => $component->duration,
                        'hourlyRate' => $component->hourly_rate,
                        'pricePerPeriod' => $component->price_per_period,
                        'salePrice' => $component->sale_price,
                        'maintenanceRate' => $component->maintenance_rate,
                        'tax' => $component->tax,
                        'paymentPeriod' => $component->payment_period,
                        'discount' => $component->discount ?? 0,
                        'additionalDescription' => $component->additional_description,
                        'products' => $productsGroupedByCategory->map(function ($product) {
                            return [
                                'id' => $product->id,
                                'articleNumber' => $product->article_number,
                                'name' => $product->name,
                                'listingPrice' => $product->listing_price,
                                'status' => $product->status ? 'active' : 'deactive',
                                'salePrice' => $product->sale_price,
                                'profit' => $product->profit,
                                'hourlyRate' => $product->hourly_rate,
                                'pricePerPeriod' => $product->price_per_period,
                                'dailyRate' => $product->daily_rate,
                                'unit' => $product->productUnit ? [
                                    'id' => $product->productUnit->id,
                                    'name' => $product->productUnit->name,
                                    'shortName' => $product->productUnit->short_name,
                                ] : null,
                                'discount' => $product->discount ?? 0,
                                'maintenancePrice' => $product->maintanence_price,
                                'maintenanceRate' => $product->maintenance_rate,
                                'quantity' => $product->quantity,
                                'tax' => $product->tax,
                                'type' => $product->payment_details_type,
                                'category' => [
                                    "id" => $product?->productCategory?->id,
                                    "name" => $product?->productCategory?->name,
                                    "defaultUnit" => $product?->productCategory?->default_unit,
                                    "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                                ]
                            ];
                        }),
                        'productCategory' => ProductCategory::where("id", $component->product_category_id)?->first(),
                        'offerId' => $component->sale_offer_id,
                    ];
                })
            ];
            if ($offerType == 'order') {
                $returnData['orderStatus'] = $item->order_status;
            }
            return $returnData;
        });
        return response()->json([
            'data' => $model_collect,
        ], 200);
    }
    //function to export latest changed CSV
    public function exportLatestCSV(Request $request)
    {
        $file_name = 'offers.csv';
        $exported_csv = ExportedCsvInfo::where('exported_csv_name', 'offer')->first();
        if (empty($exported_csv)) return $this->exportCsv();
        $exported_csv_time = Carbon::parse($exported_csv->exported_time);
        $offers = SaleOffer::where('updated_at', '>', $exported_csv_time)->get();
        return $this->createCsv($offers, $file_name, true);
    }

    public function offerForDocumentGeneration($id)
    {
        $offerTemplateId = GlobalSetting::where("key", 'offerTemplateId')?->first()?->value;

        if (!$offerTemplateId) {
            return response()->json([
                "message" => "Please assign a template document for offer module"
            ], 422);
        }
        return [
            'offer' => OrderAndOfferHelper::offerForDocumentGeneration($id),
            "offerTemplateId" => $offerTemplateId,
        ];
    }

    //function to export offers csv
    public function exportCsv()
    {
        $file_name = 'offers.csv';
        $offers = SaleOffer::all();
        return $this->createCsv($offers, $file_name);
    }

    /**
     * Create csv file for supplier
     * @param  object $offers
     * @param  string $file_name
     * @param  boolean $is_latest_exported
     * @return \Illuminate\Http\Response
     */
    private function createCsv($offers, $file_name, $is_latest_exported = false)
    {
        $columns = [
            'Offer Number', 'Type', 'Customer/Lead', 'Status',
            'Receiver Type', 'Project', 'Netto Total'
        ];
        $callback = function () use ($offers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($offers as $offer) {
                $row['Offer Number']  = $offer->sale_offer_number;
                $row['Type']    = $offer->offer_type;
                $row['Customer/Lead']  = $offer->company->company_name;
                $row['Status']  = $offer->offer_status;
                $row['Receiver Type']  = $offer->receiver_type;
                $row['Project'] =  $offer->project?->name ?? '';
                $row['Netto Total'] =  $offer->components->sum('total_netto');
                fputcsv($file, [
                    $row['Offer Number'], $row['Type'],  $row['Customer/Lead'], $row['Status'], $row['Receiver Type'],
                    $row['Project'], $row['Netto Total']
                ]);
            }
            fclose($file);
        };
        if (!$is_latest_exported) {
            $exported_csv = ExportedCsvInfo::firstOrNew(['exported_csv_name' => 'offer']);
            $exported_csv->exported_time = Carbon::now();
            $exported_csv->save();
        }
        return response()->streamDownload($callback, $file_name, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$file_name",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }

    /**
     * updates the offer status
     * @param integer $id
     * @param Request $request
     * @return JSONResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            "status" => "required|in:versendet"
        ]);
        $offer = SaleOffer::findOrFail($id);
        $offer->offer_status = $request->status;
        $offer->save();
        return response()->json([
            "success" => true,
            "message" => "Offer status has been updated."
        ]);
    }

    /**
     * calls the getELORequestData on OrderAndOfferHelper
     * @param int $id
     * @return JSONResponse
     */
    public function getELORequestData($id)
    {
        return OrderAndOfferHelper::getELORequestData($id);
    }
}