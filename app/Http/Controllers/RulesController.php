<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;

class RulesController extends Controller
{
    public function index(Request $request)
    {
        $config = config('services.licensing');
        $response = Http::withToken($request->bearerToken())
            ->get($config['vite_license_service_url'] . '/list-rules', [
                'limit_start' => $request->limit_start ?? 0,
                'limit_count' => $request->limit_count ?? 25,
            ]);
        $response->throw();

        $rules = $response->json() ?? [];

        $data = collect($rules)->map(function ($rule) {
            return [
                "id" => $rule['id'],
                "allow_overusage" => $rule['allow_overusage'],
                "config" => $rule['config'],
                "deny_if_op" => $rule['deny_if_op'],
                "deny_if_value" => $rule['deny_if_value'],
                "event_name" => $rule['event_name'],
                "last_reset" => $rule['last_reset'],
                "max_value" => $rule['max_value'],
                "min_value" => $rule['min_value'],
                "next_reset" => $rule['next_reset'],
                "product" => Product::find($rule['product_id'])?->name ?? '',
                "reset_pattern" => $rule['reset_pattern'],
                "reset_type" => $rule['reset_type'],
                "reset_value" => $rule['reset_value'],
                "role_id" => $rule['role_id'],
                "rule_name" => $rule['rule_name'],
                "status" => $rule['status'],
            ];
        });

        return response()->json([
            "data" => $data,
        ]);
    }

    public function show(Request $request, $id)
    {
        $config = config('services.licensing');
        $response = Http::withToken($request->bearerToken())
            ->post($config['vite_license_service_url'] . '/list-rule-by-id', [
                "id" => $id
            ]);
        $response->throw();

        if (!isset($response->json()[0]['id'])) {
            return response()->json([]);
        }

        $rule = $response->json()[0];

        $product = Product::find($rule['product_id']);

        return response()->json([
            "id" => $rule['id'],
            "allow_overusage" => $rule['allow_overusage'],
            "deny_if_op" => $rule['deny_if_op'],
            "deny_if_value" => $rule['deny_if_value'],
            "event_name" => $rule['event_name'],
            "last_reset" => $rule['last_reset'],
            "max_value" => $rule['max_value'],
            "min_value" => $rule['min_value'],
            "product_id" => $product ? [
                'id' => $product->id,
                'articleNumber' => $product->article_number,
                'manufacturerNumber' => $product->manufacturer_article_number,
                'name' => $product->name,
                'productCategory' => $product->productCategory?->name,
                'type' => $product->payment_details_type,
                'status' => $product->status ? 'active' : 'deactive',
                'salePrice' => $product->sale_price,
                'profit' => $product->profit,
                'productSoftware' => $product->productSoftware?->name,
            ] : [],
            "reset_pattern" => $rule['reset_pattern'],
            "reset_type" => $rule['reset_type'],
            "reset_value" => $rule['reset_value'],
            "role_id" => $rule['role_id'],
            "rule_name" => $rule['rule_name'],
            "status" => $rule['status'],
        ]);
    }
}
