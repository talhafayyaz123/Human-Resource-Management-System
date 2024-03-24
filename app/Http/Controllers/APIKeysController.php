<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Company;
use Carbon\Carbon;

class APIKeysController extends Controller
{
    public function index(Request $request)
    {
        $config = config('services.users');
        $response = Http::withToken($request->bearerToken())
            ->get($config['vite_auth_service_url'] . '/list-api-tokens', [
                'limit_start' => $request->limit_start ?? 0,
                'limit_count' => $request->limit_count ?? 25,
            ]);
        $response->throw();

        $api_keys = isset($response->json()['data']) ? $response->json()['data'] : [];

        $data = collect($api_keys)->map(function ($apiKey) {
            return [
                "id" => $apiKey['id'],
                "name" => $apiKey['name'],
                "clientId" => $apiKey['client_id'],
                "company" => Company::find($apiKey['company_id'])?->company_name ?? '-',
                "tenant" => Company::find($apiKey['tenant_id'])?->company_name ?? '-',
                "status" => $apiKey['status'],
                "roles" => $apiKey['roles'],
                "creationDate" => Carbon::parse($apiKey['creation_date']['date'])->toDateString()
            ];
        });

        return response()->json([
            "data" => $data,
            "count" => $response->json()['count']
        ]);
    }

    public function show(Request $request, $id)
    {
        $config = config('services.users');
        $response = Http::withToken($request->bearerToken())
            ->get($config['vite_auth_service_url'] . '/list-api-token-by-id', [
                "id" => $id
            ]);
        $response->throw();

        if (!isset($response->json()[0]['id'])) {
            return response()->json([]);
        }

        $api_key = $response->json()[0];

        $company = Company::find($api_key['company_id']);

        $tenant = Company::find($api_key['tenant_id']);

        return response()->json([
            "id" => $api_key['id'],
            "name" => $api_key['name'],
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
            "status" => $api_key['status'],
            "roles" => $api_key['roles'],
        ]);
    }
}
