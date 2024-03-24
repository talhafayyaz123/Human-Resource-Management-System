<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Company;
use App\Models\Product;
use Carbon\Carbon;

class LicensesController extends Controller
{
    public function index(Request $request)
    {
        $config = config('services.licensing');
        $response = Http::withToken($request->bearerToken())
            ->get($config['vite_license_service_url'] . '/list-licenses', [
                'limit_start' => $request->limit_start ?? 0,
                'limit_count' => $request->limit_count ?? 25,
            ]);
        $response->throw();

        $licenses = $response->json() ?? [];

        $data = collect($licenses)->map(function ($license) {
            return [
                "id" => $license['id'],
                "name" => $license['name'],
                "company" => Company::find($license['company_id'])?->company_name ?? '-',
                "tenant" => Company::find($license['tenant_id'])?->company_name ?? '-',
                "creationDate" => Carbon::parse($license['creation_time'])->toDateString()
            ];
        });

        return response()->json([
            "data" => $data,
            "count" => isset($response->json()['count']) ? $response->json()['count'] : 0
        ]);
    }

    public function show(Request $request, $id)
    {
        $config = config('services.licensing');
        $response = Http::withToken($request->bearerToken())
            ->post($config['vite_license_service_url'] . '/list-license-by-id', [
                "id" => $id
            ]);
        $response->throw();

        if (!isset($response->json()[0]['id'])) {
            return response()->json([]);
        }

        $license = $response->json()[0];

        $company = Company::find($license['company_id']);

        $tenant = Company::find($license['tenant_id']);

        $rules = isset($license['rules']) ? $license['rules'] : [];

        $product_info = collect($license['product_info'] ?? []);

        return response()->json([
            "id" => $license['id'],
            "name" => $license['name'],
            "product_info" => $product_info,
            "status" => $license['status'],
            "company" => $company ? [
                'id' => $company->id,
                'companyName' => $company->company_name,
                'vatId' => $company->vat_id,
                'url' => $company->url,
                'type' => $company->type,
                'customerType' => $company->customer_type,
                'companyNumber' => $company->company_number,
                'state' => "",
                'city' => "",
                'country' => "",
                'address' => "",
                'notes' => $company->notes,
                'status' => $company->status,
                'expiryDate' => $company->expiry_dt ? Carbon::parse($company->expiry_dt)->toDateString() : '',
                'termsOfPayment' => $company->terms_of_payment,
            ] : null,
            "tenant" => $tenant ? [
                'id' => $company->id,
                'companyName' => $company->company_name,
                'vatId' => $company->vat_id,
                'url' => $company->url,
                'type' => $company->type,
                'customerType' => $company->customer_type,
                'companyNumber' => $company->company_number,
                'state' => "",
                'city' => "",
                'country' => "",
                'address' => "",
                'notes' => $company->notes,
                'status' => $company->status,
                'expiryDate' => $company->expiry_dt ? Carbon::parse($company->expiry_dt)->toDateString() : '',
                'termsOfPayment' => $company->terms_of_payment,
            ] : null,
            "rules" => $rules,
            "events" => $license['event_values'] ?? [],
            "config" => $license['config'],
            "products" => Product::whereIn("id", $product_info->map(fn ($product) => $product["id"]))->get()->map(function ($product) use ($product_info) {

                // find the product based on current loop product from the $product_info array
                $found_product_info = $product_info->first(function ($item) use ($product) {
                    return $item["id"] == $product["id"];
                });

                return [
                    'id' => $product->id,
                    'articleNumber' => $product->article_number,
                    'manufacturerNumber' => $product->manufacturer_article_number,
                    'name' => $product->name,
                    'listingPrice' => $product->listing_price,
                    'status' => $product->status ? 'active' : 'deactive',
                    'salePrice' => $product->sale_price,
                    'profit' => $product->profit,
                    'discount' => $product->discount,
                    'quantity' => isset($found_product_info["quantity"]) ? $found_product_info["quantity"] :  1,
                    'tax' => $product->tax,
                    'maintenancePrice' => $product->maintanence_price,
                    'maintenanceRate' => $product->maintenance_rate,
                    'category' => [
                        "id" => $product?->productCategory?->id,
                        "name" => $product?->productCategory?->name,
                        "defaultUnit" => $product?->productCategory?->default_unit,
                        "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                    ],
                    //Additional info
                    'description' => $product->description,
                    'unit' => $product->productUnit ? [
                        'id' => $product->productUnit->id,
                        'name' => $product->productUnit->name,
                        'shortName' => $product->productUnit->short_name,
                    ] : null,
                    'type' => $product->payment_details_type,
                    'paymentPeriod' => $product->paymentPeriod ? [
                        'id' => $product->paymentPeriod?->id,
                        'name' => $product->paymentPeriod?->name,
                        'billingCycle' => $product->paymentPeriod?->billing_cycle,
                        'createdAt' => Carbon::parse($product->paymentPeriod?->created_at)->format('d/m/y'),
                    ] : null,
                    'productSoftware' => $product->productSoftware,
                    "rules" => $product->rules->map(function ($item) {
                        return [
                            'id' => $item->rule_id
                        ];
                    }),
                ];
            })->unique(),
        ]);
    }
}
