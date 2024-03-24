<?php

namespace App\Http\Controllers;

use App\Http\Resources\ModuleHistoryResource;
use App\Models\CloudInfrastructre;
use App\Models\CloudServer;
use App\Models\System;
use App\Models\SystemTenant;
use App\Models\CloudInfrastructureTenants;
use App\Models\CloudInfrastructureTenantRepositories;
use App\Utils\Logger;
use Illuminate\Http\Request;
use App\Traits\SetGlobalNumber;
use Illuminate\Support\Facades\DB;
use Spatie\FlareClient\Time\SystemTime;
use App\Traits\CustomHelper;

class SystemCloudController extends Controller
{
    use SetGlobalNumber, CustomHelper;
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
        $model = new System;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $systems = $model->where('type', 'cloud')->when($request->withTenant, function ($query) {
            $query->has('tenant');
        })->when($request->instanceType, function ($query) use ($request) {
            $query->where('instance_type', $request->instanceType);
        })->where('system_name', 'like', '%' . $request->search . '%')->paginate($per_page)->withQueryString()->through(fn ($item) => [
            'id' => $item->id,
            'systemNumber' => $item->system_number,
            'systemName' => $item->system_name ?? '',
            'type' => $item->type,
            'instanceType' => $item->instance_type,
            'subType' => $item->sub_type,
        ]);
        return response()->json([
            'data' => $systems,
        ]);
    }

    /**
     * Display a listing of the resource for clouds.
     *
     * @return \Illuminate\Http\Response
     */
    public function cloudIndexForTenant(Request $request)
    {
        $per_page = !empty($request->perPage) ? $request->perPage : 10;
        $tenants = SystemTenant::when(!empty($request->instanceType), function ($query) use ($request) {
            return $query->whereHas('system', function ($query) use ($request) {
                $query->where('instance_type', $request->instanceType);
            });
        })
            ->when($request->customerId, function ($query) use ($request) {
                $query->where('customer_id', $request->customerId ?? null);
            })
            ->where('name', 'like', '%' . $request->search . '%')
            ->with('system')
            ->paginate($per_page)
            ->withQueryString();

        $model_data =  $tenants->map(function ($item) {
            if (!$item->system) {
                return null; // exclude null values
            }
            return [
                'id' => $item->id, // use a different attribute if necessary
                'systemNumber' => $item->system->system_number,
                'systemName' => $item->system->system_name ?? '',
                'instanceType' => $item->system->instance_type,
                'subType' => $item->system->sub_type,
                'type' => $item->system->type,
                'tenantName' => $item->name,
                'url' => $item->url ?? ''
            ];
        })->filter();

        return response()->json([
            'data' => $model_data,
            'meta' => [
                'current_page' => $tenants->currentPage(),
                'from' => $tenants->firstItem(),
                'last_page' => $tenants->lastPage(),
                'path' => request()->url(),
                'per_page' => $tenants->perPage(),
                'to' => $tenants->lastItem(),
                'total' => $tenants->total(),
            ]
        ]);
    }


    public function showSystemForTenant($id)
    {
        $tenant = SystemTenant::findOrFail($id);
        $data = [
            "tenant" => !empty($tenant) ? [
                'tenantName' => $tenant->name,
                'systemUser' => ["id" => $tenant->company?->id, "companyName" => $tenant->company?->company_name],
                'systemCloud' => ["id" => $tenant->system->id, "systemName" => $tenant->system->system_name],
                'url' => $tenant->url ?? '',
                'repositoriesArray' => $tenant->repositories->map(function ($repository) {
                    return [
                        'name' => $repository->name,
                        'text' => $repository->text,
                    ];
                }),
                "databaseCloud" => $tenant->databaseClouds->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];
                }),
                "distributedFileSystem" => $tenant->distributedFilesystems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];
                })
            ] : [],
        ];
        return response()->json(['systems' => $data]);
    }

    public function deleteTenant($id)
    {
        $tenant = SystemTenant::findOrFail($id);
        $tenant->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }

    public function deleteCloudTenant($id)
    {
        $tenant = CloudInfrastructureTenants::where('id', $id)->first();
        if (!empty($tenant->cloudTenantRepositories)) {
            $tenant->cloudTenantRepositories()->delete();
        }
        $tenant->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    public function deleteCloudTenantRepository($repositoryId)
    {
        $tenant = CloudInfrastructureTenantRepositories::find($repositoryId);
        $tenant->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    public function createCloudTenantRepository(Request $request)
    {
        $request->validate([
            "name" => "required",
            "dataSize" => "required",
            "dataBaseSize" => "required",
            "cloudTenantId" => "required",
        ]);
        $repository = new CloudInfrastructureTenantRepositories();
        $repository->name = $request->name;
        $repository->database_size = $request->dataBaseSize;
        $repository->data_size = $request->dataSize;
        $repository->cloud_tenant_id = $request->cloudTenantId;
        $repository->save();

        return response()->json(['message' => 'Record created successfully.'], 200);
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
            "type" => "required",
            "subType" => "required|string",
            "instanceType" => "required",
            "name" => "required|string",
            'cloudServers.*.serverIp' => 'nullable|string',
            'cloudServers.*.port' => 'nullable|string',
            'cloudServers.*.userAddress' => 'nullable|string',
            'cloudServers.*.password' => 'nullable|string',
            'cloudServers.*.isMaster' => 'nullable|boolean',
        ]);
        $system = new System();
        $this->saveData($system, $request);
        return response()->json(['message' => 'Record created successfully.'], 200);
    }

    public function storeCloudInfrastructureTenant(Request $request)
    {
        $request->validate([
            "systemId" => "required",
            "partnerId" => "required",
            "systemUser" => "required",
            "tenantName" => "required|string",
            "tenantEmail" => "required|email",
            'repositoriesArray.*.databaseSize' => 'required|string',
            'repositoriesArray.*.dataSize' => 'required|string',
            'repositoriesArray.*.name' => 'required|string',
        ]);

        $system = new CloudInfrastructureTenants();
        $this->saveCloudTenantData($system, $request);
        return response()->json(['message' => 'Record created successfully.'], 200);
    }

    public function getCloudInfrastructureTenants($id)
    {
        $tenants =  CloudInfrastructureTenants::with('cloudTenantRepositories')->where('system_id', $id)->get();
        return response()->json(['tenants' => $tenants->map(function ($tenant) {
            return [
                "id" => $tenant->id,
                "tenant_name" => $tenant->tenant_name,
                "customer_id" => $tenant->customer_id,
                "partner_id" => $tenant->partner_id,
                "system_id" => $tenant->system_id,
                "created_at" => $tenant->created_at,
                "updated_at" => $tenant->updated_at,
                "partnerName" => $tenant->partner?->company_name ?? '',
                "companyName" => $tenant->customer?->company_name ?? '',
                "cloud_tenant_repositories" => $tenant->cloudTenantRepositories->load('eloBusinessSolutions'),
                "tenant_email" => $tenant->tenant_email,
            ];
        })]);
    }

    public function getCustomerTenants(Request $request, $id)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new CloudInfrastructureTenants();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $cloud_infrastructures = $model->with('cloudTenantRepositories')->where('partner_id', $id)->where('customer_id', $request->companyId)->orderBy('created_at')
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($tenant) => [
                "clusterName" => $tenant->cloudInfrastructure?->name ?? '',
                "clusterInstance" => $tenant->cloudInfrastructure?->instance_type ?? '',
                "clusterType" => $tenant->cloudInfrastructure?->sub_type ?? '',
                "id" => $tenant->id,
                "tenantName" => $tenant->tenant_name,
                "tenantEmail" => $tenant->tenant_email,
                "customerId" => $tenant->customer_id,
                "partnerId" => $tenant->partner_id,
                "systemId" => $tenant->system_id,
                "createdAt" => $tenant->created_at,
                "updatedAt" => $tenant->updated_at,
                "partnerName" => $tenant->partner?->company_name ?? '',
                "companyName" => $tenant->customer?->company_name ?? '',
                "tenantRepositories" => $tenant->cloudTenantRepositories
            ]);
        return response()->json([
            'data' => $cloud_infrastructures
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = System::where('id', $id)->firstOrFail();
        return response()->json([
            'systems' => [
                'id' => $model->id,
                "systemName" => $model->system_name,
                'subType' => $model->sub_type,
                "instanceType" => $model->instance_type,
                'cloudServers' => !empty($model->cloudServers) ? $model->cloudServers->map(function ($server) {
                    return [
                        'serverIp' => $server->server_ip,
                        'port' => $server->port,
                        'userAddress' => $server->user_address,
                        'password' => $server->password,
                        'isMaster' => $server->is_master
                    ];
                }) : null
            ]
        ]);
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
            "type" => "required",
            "subType" => "required|string",
            "instanceType" => "required",
            "name" => "required|string",
            'cloudServers.*.serverIp' => 'nullable|string',
            'cloudServers.*.port' => 'nullable|string',
            'cloudServers.*.userAddress' => 'nullable|string',
            'cloudServers.*.password' => 'nullable|string',
            'cloudServers.*.isMaster' => 'nullable|boolean',
        ]);
        $system = System::findOrFail($id);
        $this->saveData($system, $request, false);
    }

    /**
     * Update the system cloud tenant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCloudTenant(Request $request, $id)
    {
        $request->validate([
            "systemId" => "required",
            "partnerId" => "required",
            "systemUser" => "required",
            "tenantName" => "required|string",
            "tenantEmail" => "required|email",
            'repositoriesArray.*.databaseSize' => 'required',
            'repositoriesArray.*.dataSize' => 'required',
            'repositoriesArray.*.name' => 'required|string',
            'repositoriesArray.*.businessSolutions' => 'nullable|array',
            'repositoriesArray.*.businessSolutions.*.id' => 'required',
        ]);

        $tenant = CloudInfrastructureTenants::where('id', $id)->first();
        $this->saveCloudTenantData($tenant, $request, true, $id);
        return response()->json(["success" => true, "message" => "Tenant updated successfully!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = System::find($id);
        $model->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }


    /**
     * Save system and cloud server data to the database.
     *
     * @param System $system
     * @param Request $request
     * @return System
     */
    private function saveData(System $system, Request $request, $is_new = true): System
    {
        return DB::transaction(function () use ($system, $request, $is_new) {
            // Set system properties from the request
            $system->type = $request->type;
            $system->instance_type = $request->instanceType;
            $system->system_name = $request->name;
            $system->sub_type = $request->subType;
            if ($is_new) {
                $number = $this->globalNumber($system, 'system_cloud', 'DH', 1000);
                $system->system_number = $number;
            }

            // Save the system to the database
            $system->save();

            if (!empty($system->cloudServers)) {
                $system->cloudServers()->delete();
            }

            // If there are cloud servers in the request, save them to the database
            if (!empty($request->cloudServers)) {
                foreach ($request->cloudServers as $server) {
                    $cloud_servers = new CloudServer();
                    $cloud_servers->server_ip = $server['serverIp'];
                    $cloud_servers->port = $server['port'];
                    $cloud_servers->user_address = $server['userAddress'];
                    $cloud_servers->password = $server['password'];
                    $cloud_servers->is_master = $server['isMaster'] ?? null;
                    $cloud_servers->systems()->associate($system);
                    $cloud_servers->save();
                }
            }
            // Return the saved system
            return $system;
        });
    }

    /**
     * Save system tenants and cloud infrastructure repositories data to the database.
     *
     * @param System $system
     * @param Request $request
     * @return System
     */
    private function saveCloudTenantData(CloudInfrastructureTenants $system, Request $request, $is_update = false, $id = ''): CloudInfrastructureTenants
    {
        return DB::transaction(function () use ($system, $request, $is_update, $id) {
            // Set system properties from the request
            if (!$is_update) {
                $system->system_id = $request->systemId;
            }

            $system->partner_id = $request->partnerId;
            $system->customer_id = $request->systemUser;
            $system->tenant_name = $request->tenantName;
            $system->tenant_email = $request->tenantEmail;

            // Save the system to the database
            $system->save();
            if ($id) {
                $insertedId = $id;
            } else {
                $insertedId = $system->id;
            }

            if (!empty($system->cloudTenantRepositories)) {
                $system->cloudTenantRepositories()->delete();
            }

            // If there are cloud servers in the request, save them to the database
            if (!empty($request->repositoriesArray)) {
                foreach ($request->repositoriesArray as $server) {
                    $cloud_servers = new CloudInfrastructureTenantRepositories();
                    $cloud_servers->cloud_tenant_id = $insertedId;
                    $cloud_servers->database_size = $server['databaseSize'];
                    $cloud_servers->data_size = $server['dataSize'];
                    $cloud_servers->name = $server['name'];
                    $cloud_servers->save();
                    if (!empty($server['businessSolutions'])) {
                        $business_solution_collect = collect($server['businessSolutions']);
                        $business_solution_collect_ids = $business_solution_collect->pluck('id');
                        $cloud_servers->eloBusinessSolutions()->sync($business_solution_collect_ids);
                    }
                }
            }
            // Return the saved system
            return $system;
        });
    }


    public function getCloudInfrastructureTenant($id)
    {
        $tenant = CloudInfrastructureTenants::findOrFail($id);
        return response()->json(
            [
                'tenant' => [
                    "id" => $tenant->id,
                    "tenantName" => $tenant->tenant_name,
                    "customerId" => $tenant->customer_id,
                    "partnerId" => $tenant->partner_id,
                    "systemId" => $tenant->system_id,
                    "createdAt" => $tenant->created_at,
                    "updatedAt" => $tenant->updated_at,
                    "partnerName" => $tenant->partner?->company_name ?? '',
                    "companyName" => $tenant->customer?->company_name ?? '',
                    "cloudTenantRepositories" => $tenant->cloudTenantRepositories
                ]
            ]
        );
    }
}
