<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Company;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $config = config('services.users');
        $response = Http::withToken($request->bearerToken())
            ->get($config['vite_auth_service_url'] . '/list-roles', [
                'limit_start' => $request->limit_start ?? 0,
                'limit_count' => $request->limit_count ?? 25,
                'search_string' => $request->search_string ?? "",
            ]);
        $response->throw();

        $roles = isset($response->json()['data']) ? $response->json()['data'] : [];

        $data = collect($roles)->map(function ($role) {
            return [
                "id" => $role['id'],
                "active" => $role['active'],
                "can_register" => $role['can_register'],
                "is_all_companies" => $role['is_all_companies'],
                "is_all_tenants" => $role['is_all_tenants'],
                "permissions" => $role['permissions'],
                "title" => $role['title'],
                "company_id" => $role['company_id'],
                "tenant_id" => $role['tenant_id'],
                "company" => Company::find($role['company_id'])?->company_name ?? '-',
                "tenant" => Company::find($role['tenant_id'])?->company_name ?? '-',
            ];
        });

        return response()->json([
            "data" => $data,
            "count" => isset($response->json()['count']) ? $response->json()['count'] : 0
        ]);
    }
}
