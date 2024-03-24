<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Company;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $config = config('services.users');
        $response = Http::withToken($request->bearerToken())
            ->get($config['vite_auth_service_url'] . '/list-permissions', [
                'limit_start' => $request->limit_start ?? 0,
                'limit_count' => $request->limit_count ?? 25,
                'search_string'=> $request->search_string ?? '',
            ]);
        $response->throw();

        $permissions = isset($response->json()['data']) ? $response->json()['data'] : [];

        $data = collect($permissions)->map(function ($permission) {
            return [
                "id" => $permission['id'],
                "active" => $permission['active'],
                "can_be_assigned" => $permission['can_be_assigned'],
                "description" => $permission['description'],
                "grouping" => $permission['grouping'],
                "is_all_companies" => $permission['is_all_companies'],
                "is_all_tenants" => $permission['is_all_tenants'],
                "needs_permission" => $permission['needs_permission'],
                "scope" => $permission['scope'],
                "title" => $permission['title'],
                "value" => $permission['value'],
                "company_id" => $permission['company_id'],
                "tenant_id" => $permission['tenant_id'],
                "company" => Company::find($permission['company_id'])?->company_name ?? '-',
                "tenant" => Company::find($permission['tenant_id'])?->company_name ?? '-',
            ];
        });

        return response()->json([
            "data" => $data,
            "count" => isset($response->json()['count']) ? $response->json()['count'] : 0
        ]);
    }
}
