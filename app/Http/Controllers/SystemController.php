<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Helpers\GlobalSettingHelper;
use App\Http\Resources\ModuleHistoryResource;
use App\Models\CloudServer;
use App\Models\EloVersion;
use App\Models\GlobalSetting;
use App\Models\ProductSoftware;
use App\Models\System;
use App\Models\SystemTenant;
use App\Models\TenantRepository;
use App\Traits\CustomHelper;
use App\Utils\Logger;
use Exception;
use Facades\App\Services\InstallSystemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as staticRequest;
use phpseclib3\Net\SSH2 as NetSSH2;
use phpseclib3\Net\SFTP as NetSFTP;

class SystemController extends Controller
{
    use CustomHelper;

    /**
     *Runs when instance is initiated
     */

    public function __construct()
    {
        $this->middleware('check.permission:system.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:system.create', ['only' => ['store']]);
        $this->middleware('check.permission:system.edit', ['only' => ['update']]);
        $this->middleware('check.permission:system.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = !empty($request->perPage) ? $request->perPage : 30;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $systems = System::when($request->forCustomerPortal, function ($query) use ($request) {
            $query->whereIn('type', ['premise', 'hosting']);
        })->when($request->type, function ($query) use ($request) {
            $query->where('type', $request->type ?? 'premise');
        })->when($request->instanceType, function ($query) use ($request) {
            $query->where('instance_type', $request->instanceType);
        })->when($request->forPartnerPortal, function ($query) use ($request) {
            $query->where('type', 'hosting')->where('partner_id', $request->partnerId);
        })
        ->when($request->customerId, function ($query) use ($request) {
            $query->where('customer_id', $request->customerId ?? null);
        })
        ->where('server_ip', 'like', '%' . $request->search . '%')->paginate($per_page);
        if (!$request->forCustomerPortal) {
            $systems->withQueryString();
        }

        $systems->through(fn ($item) => [
            'id' => $item->id,
            'systemNumber' => $item->system_number,
            'systemName' => $item->system_name ?? '',
            'type' => $item->type,
            'customer' => $item->company->company_name ?? '',
            'instanceType' => $item->instance_type,
            'operatingSystem' => $item?->operatingSystem?->name,
            'software' => $item?->software?->name,
            'eloVersion' => $item?->version?->name,
            'serverIp' => $item->server_ip,
            'tennantName' => $item->tenant->name ?? '',
            'virtualMachine' => $item->host_virtual_machine,
            'host' => $item->host,
            'subType' => $item->sub_type,
            'isDropdown' => false,
            'hostingType' => $item->hosting_type,
            'companies' => isset($item->cloudCustomers) ? $item->cloudCustomers->map(function ($customer) {
                return [
                    'companyName' => $customer->company_name,
                    'companyNumber' => $customer->company_number,
                    'companyPhone' => $customer->phone,
                    'companyType' => $customer->type,
                ];
            }) : []
        ]);
        if ($sort_by && $sort_order) {
            $sorted_result = $this->applySorting($systems->getCollection(), $sort_by, $sort_order);
            $systems->setCollection($sorted_result);
        }
        return response()->json([
            'data' => $systems,
        ], 200);
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
            "type" => "required|string",
            "instanceType" => "required_if:type,premise,hosting",
            "serverIp" => "required_if:type,premise,hosting|string",
            "systemUser" => "required_if:type,premise,hosting",
            "serverPort" => "required_if:type,premise,hosting|integer",
            "username" => "required_if:type,premise,hosting|string",
            "password" => "required_if:type,premise,hosting|string",
            "operatingSystemId" => "required_if:type,premise,hosting|integer|exists:App\Models\OperatingSystem,id",
            "software" => "required_if:type,premise,hosting|integer|exists:App\Models\ProductSoftware,id",
            "eloVersion" => "required_if:type,premise,hosting|integer|exists:App\Models\EloVersion,id",
            "systemName" => "required_if:type,premise|string",
            "virtualMachine" => "required_if:type,hosting|string",
            "host" => "required_if:type,hosting|array",
            "tenant" => "required_if:type,cloud",
            "tenant.systemUser" => "required_if:type,cloud",
            "tenant.systemCloud" => "required_if:type,cloud",
            "tenant.tenantName" => "required_if:type,cloud|regex:/^[A-Za-z\-_]+$/",
            "tenant.repositoriesArray" => "required_if:type,cloud|array",
            "tenant.url" => "nullable|string",
            "partnerId" => "nullable|string|exists:App\Models\Company,id"
        ], [
            'tenant.systemCloud' => 'Please select a system',
            'tenant.systemUser.required_if' => 'Customer field is required.',
            'tenant.tenantName.required_if' => 'Tenant Name field is required.',
            'tenant.tenantName.regex' => 'Tenant Name must only contain letters, dashes, and underscores',
            'tenant.repositoriesArray' => 'Please enter some repositories'
        ]);

        //Create System
        $model = new System;
        if ($request->type == 'cloud') {
            $this->saveCloudData($request, true, 'createCloudSystem');
        } else {
            $action = 'createOnPremiseSystem';
            if ($request->type == 'hosting') {
                $action = 'createHostingSystem';
            }
            $this->saveData($model, $request, true, $action);
        }

        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'System'])], 200);
    }

    private function saveCloudData($request, $is_new = false, $action): ?SystemTenant
    {
        if ($is_new) {
            $system_tenant = new SystemTenant;
        } else {
            $system_tenant = SystemTenant::findOrFail($request->id);
        }
        $system_tenant->system_id = $request->tenant['systemCloud']['id'];
        $system_tenant->name = $request->tenant['tenantName'];
        $system_tenant->customer_id = $request->tenant['systemUser']['id'];
        $system_tenant->url = $request->tenant['url'] ?? '';
        $system_tenant->save();
        if (isset($system_tenant->repositories)) {
            $system_tenant->repositories()->delete();
        }
        foreach ($request->tenant['repositoriesArray'] as $repository) {
            $repositories = new TenantRepository;
            $repositories->name = $repository['name'];
            $repositories->text = $repository['text'];
            $repositories->tenant()->associate($system_tenant);
            $repositories->save();
        }
        $database_colud_ids = collect($request->tenant['databaseCloud'])->pluck('id')->toArray();
        $system_tenant->databaseClouds()->sync($database_colud_ids);

        $file_system_ids = collect($request->tenant['distributedFileSystem'])->pluck('id')->toArray();
        $system_tenant->distributedFilesystems()->sync($file_system_ids);

        $content = [
            'moduleAction' => $action,
            'data' => [
                'systemTenant' => $system_tenant->toArray(),
                'repositories' => $system_tenant->repositories()->get()->toArray(),
                'databaseClouds' => $system_tenant->databaseClouds()->get()->toArray(),
                'distributedFilesystems' => $system_tenant->distributedFilesystems()->get()->toArray(),
            ]
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return $system_tenant;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = System::where('id', $id)->first();
        return response()->json([
            'systems' => [
                'id' => $model->id,
                "systemUser" => $model->customer_id,
                "type" => $model->type,
                "serverIp" => $model->server_ip,
                "serverPort" => $model->server_port,
                "systemName" => $model->system_name,
                "username" => $model->username,
                "password" => $model->password,
                "instanceType" => $model->instance_type,
                "operatingSystem" => $model->operatingSystem,
                "software" => $model->software,
                "eloVersion" => $model->version,
                "namespace" => $model->namespace,
                'tennantName' => $model->tenant->name ?? '',
                'virtualMachine' => $model->host_virtual_machine,
                'host' => $model->system_host_id,
                'subType' => $model->sub_type,
                'hostingType' => $model->hosting_type,
                'reverseClientId' => $model->reverse_client_id,
                'systemLanguage' => $model?->hostDockerField?->system_language,
                'systemSize' => $model?->hostDockerField?->system_size,
                'databaseSize' => $model?->hostDockerField?->database_size,
                'url' => $model?->hostDockerField?->url,
                'partnerId' => $model->partner_id ?? null,
                "systemProducts" => $model->products->map(function ($product) {
                    return [
                        "id" => $product->id,
                        "name" => $product->name,
                        "quantity" => $product->pivot->quantity,
                        'articleNumber' => $product->article_number,
                        'salePrice' => $product->sale_price,
                        'listingPrice' => $product->listing_price,
                    ];
                }),
                "databaseCloud" => $model->databaseClouds->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];
                }),
                "distributedFileSystem" => $model->distributedFilesystems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];
                }),
                "installedProducts" => $model->installedProducts->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'articleNumber' => $product->article_number,
                        'name' => $product->name,
                        'listingPrice' => $product->listing_price,
                        'status' => $product->status ? 'active' : 'deactive',
                        'salePrice' => $product->sale_price,
                        'discount' => $product->discount,
                        'quantity' => $product->pivot->quantity,
                        'tax' => $product->tax
                    ];
                }),
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
            "type" => "required|string",
            "instanceType" => "required_if:type,premise,hosting",
            "serverIp" => "required_if:type,premise,hosting|string",
            "systemUser" => "required_if:type,premise,hosting",
            "serverPort" => "required_if:type,premise,hosting|integer",
            "username" => "required_if:type,premise,hosting|string",
            "password" => "required_if:type,premise,hosting|string",
            "operatingSystemId" => "required_if:type,premise,hosting|integer|exists:App\Models\OperatingSystem,id",
            "software" => "required_if:type,premise,hosting|integer|exists:App\Models\ProductSoftware,id",
            "eloVersion" => "required_if:type,premise,hosting|integer|exists:App\Models\EloVersion,id",
            "systemName" => "required_if:type,premise|string",
            "virtualMachine" => "required_if:type,hosting|string",
            "host" => "required_if:type,hosting|array",
            "tenant" => "required_if:type,cloud",
            "tenant.systemUser" => "required_if:type,cloud",
            "tenant.systemCloud" => "required_if:type,cloud",
            "tenant.tenantName" => "required_if:type,cloud|regex:/^[A-Za-z\s\-_]+$/",
            "tenant.repositoriesArray" => "required_if:type,cloud|array",
            "tenant.url" => "nullable|string",
            "partnerId" => "nullable|string|exists:App\Models\Company,id"
        ], [
            'tenant.systemCloud' => 'Please select a system',
            'tenant.systemUser.required_if' => 'Customer field is required.',
            'tenant.tenantName.required_if' => 'Tenant Name field is required.',
            'tenant.tenantName.regex' => 'Tenant Name must only contain letters, dashes, and underscores',
            'tenant.repositoriesArray' => 'Please enter some repositories'
        ]);

        //Update System
        $model = System::where("id", $id)->first();
        if ($request->type == 'cloud') {
            $request->request->add(['id' => $id]);
            $this->saveCloudData($request, false, 'updateCloudSystem');
        } else {
            $action = 'updateOnPremiseSystem';
            if ($request->type == 'hosting') {
                $action = 'updateHostingSystem';
            }
            $this->saveData($model, $request, false, $action);
        }
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }

    /**
     * Saves the data based on provided resource item
     *
     * @param   object  $model
     * @param   object  $request
     * @param   array   Response
     */
    public function saveData($model, $request, $is_new = false, $action)
    {
        $model->customer_id = $request->systemUser;
        $model->type = $request->type;
        $model->server_ip = $request->serverIp ?? '';
        $model->server_port = $request->serverPort ?? '';
        $model->username = $request->username ?? '';
        $model->instance_type = $request->instanceType ?? '';
        $model->operating_system_id = $request->operatingSystemId ?? null;
        $model->product_software_id = $request->software ?? null;
        $model->elo_version_id  = $request->eloVersion ?? null;
        $model->password = $request->password ?? '';
        $model->tenant_name = $request->tenantName ?? '';
        $model->system_name = $request->systemName ?? '';
        $model->host_virtual_machine = $request->virtualMachine ?? '';
        $model->sub_type = $request->subType ?? null;
        $model->system_host_id = $request->host['id'] ?? null;
        $model->reverse_client_id = $request->reverseClientId ?? '';
        $model->partner_id = $request->partnerId ?? null;
        if ($is_new) {
            $global_invoice_setting = GlobalSetting::where('key', 'system')->first();
            if (empty($global_invoice_setting)) {
                $global_setting = new GlobalSetting;
                $global_setting->key = 'system';
                $global_setting->value = 1000;
                $global_setting->save();
            } else {
                $global_setting = tap(DB::table('global_settings')->where('key', 'system'))->update([
                    'value' => DB::raw("value+1")
                ])->first();
            }
            $model->system_number = ('DH' . date("Y") . '-' . $model->getNextId() . '-' . $global_setting->value);
        }
        $model->hosting_type = $request->hostingType;
        $model->save();
        if ($request->type === 'hosting' && $request->hostingType === 'docker') {
            if ($model->hostDockerField) {
                $model->hostDockerField()->delete();
            }
            $model->hostDockerField()->create([
                'database_size' => $request->databaseSize,
                'system_language' => $request->systemLanguage, 'url' => $request->url ?? '',
                'system_size' => $request->systemSize
            ]);
        }
        if (isset($model->operating_system) && $model->operating_system == 'linux') {
            $store_systems = [];
            if (isset($request->systemProducts)) {
                $store_systems = collect($request->systemProducts)->mapWithKeys(function ($item) {
                    return [$item['id'] => ['quantity' => $item['quantity']]];
                });
            }
            $model->products()->sync($store_systems);
        }

        $model->databaseClouds()->sync($request->databaseCloud);
        $model->distributedFilesystems()->sync($request->distributedFileSystem);
        $content = [
            'moduleAction' => $action,
            'data' => [
                'system' => $model->toArray(),
                'hostDockerField' => $model->hostDockerField()->get()->toArray(),
                'products' => $model->products()->get()->toArray(),
                'databaseClouds' => $model->databaseClouds()->get()->toArray(),
                'distributedFilesystems' => $model->distributedFilesystems()->get()->toArray(),
            ]
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $model);
        return $model;
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
        if ($model->hostDockerField) {
            $model->hostDockerField()->delete();
        }
        $content = [
            'moduleAction' => 'deleteAction',
            'data' => [
                'system' => $model->toArray(),
            ]
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        $model->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    /**
     * Restore the previously deleted source.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $model = System::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }

    /**
     * Downloads the rdp file.
     *
     * @param  \App\Models\System $system
     * @return \Illuminate\Http\Response
     */
    public function download(System $system)
    {
        $file_name = $system->system_number . '_' . $system->operating_system . '.rdp';
        $file_array = strtr(Constants::RDP_FILE_CONSTANT, ['%server_ip' => $system->server_ip, '%server_port' => $system->server_port, '%user_name' => $system->username]);
        return response()->json(['file' => $file_array, 'fileName' => $file_name]);
    }

    /**
     * Display a listing of the resource.
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function getSystemByCompany(Request $request)
    {
        $model = new System;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $premise_model = $model->where('systems.type', 'premise')->where('customer_id', $request->get('id'))->get()->map(fn ($item) => [
            'id' => $item->id,
            'systemNumber' => $item->system_number,
            'systemName' => $item->system_name ?? '',
            'type' => $item->type,
            'customer' => $item->company->company_name ?? '',
            'instanceType' => $item->instance_type,
            'operatingSystem' => $item?->operatingSystem?->name,
            'serverIp' => $item->server_ip,
            'tennantName' => $item->tenant->name ?? '',
            'virtualMachine' => $item->host_virtual_machine,
            'host' => $item->host,
            'subType' => $item->sub_type,
            'isDropdown' => false,
            'companies' => isset($item->cloudCustomers) ? $item->cloudCustomers->map(function ($customer) {
                return [
                    'companyName' => $customer->company_name,
                    'companyNumber' => $customer->company_number,
                    'companyPhone' => $customer->phone,
                    'companyType' => $customer->type,
                ];
            }) : []
        ]);
        $id = $request->get('id');
        $cloud_model = $model->where('systems.type', 'cloud')->whereHas('tenant', function ($query) use ($id) {
            $query->where('customer_id', $id);
        })->get()->map(fn ($item) => [
            'id' => $item->id,
            'systemNumber' => $item->system_number,
            'systemName' => $item->system_name ?? '',
            'type' => $item->type,
            'customer' => $item->company->company_name ?? '',
            'instanceType' => $item->instance_type,
            'operatingSystem' => $item?->operatingSystem?->name,
            'serverIp' => $item->server_ip,
            'tennantName' => $item->tenant->name ?? '',
            'virtualMachine' => $item->host_virtual_machine,
            'host' => $item->host,
            'subType' => $item->sub_type,
            'isDropdown' => false,
            'companies' => isset($item->cloudCustomers) ? $item->cloudCustomers->map(function ($customer) {
                return [
                    'companyName' => $customer->company_name,
                    'companyNumber' => $customer->company_number,
                    'companyPhone' => $customer->phone,
                    'companyType' => $customer->type,
                ];
            }) : []
        ]);
        $hosting_model = $model->where('systems.type', 'hosting')->where('customer_id', $request->get('id'))->get()->map(fn ($item) => [
            'id' => $item->id,
            'systemNumber' => $item->system_number,
            'systemName' => $item->system_name ?? '',
            'type' => $item->type,
            'customer' => $item->company->company_name ?? '',
            'instanceType' => $item->instance_type,
            'operatingSystem' => $item?->operatingSystem?->name,
            'serverIp' => $item->server_ip,
            'tennantName' => $item->tenant->name ?? '',
            'virtualMachine' => $item->host_virtual_machine,
            'host' => $item->host,
            'subType' => $item->sub_type,
            'isDropdown' => false,
            'companies' => isset($item->cloudCustomers) ? $item->cloudCustomers->map(function ($customer) {
                return [
                    'companyName' => $customer->company_name,
                    'companyNumber' => $customer->company_number,
                    'companyPhone' => $customer->phone,
                    'companyType' => $customer->type,
                ];
            }) : []
        ]);
        return response()->json([
            "success" => true,
            "premise" => $premise_model,
            "cloud" => $cloud_model,
            "hosting" => $hosting_model,
        ]);
    }


    /**
     * install products
     * @param  object $system
     * @return \Illuminate\Http\Response
     */
    public function install(System $system)
    {

        return response()->stream(function () use ($system) {
            try {
                $host = $system->server_ip;
                $port = $system->server_port ?? 22;
                $username = $system->username;
                $password = $system->password;
                //getting related products of a system
                $products = $system->products;
                $ssh = new NetSSH2($host, $port);
                if (!$ssh->login($username, $password)) {
                    echo 'data: Not connected either username or password is incorrect';
                    /** use to stream the data */
                    ob_flush();
                    flush();
                    echo "\n\n";
                    /** */
                    return;
                }
                $sftp = new NetSFTP($host, $port);
                if (!$sftp->login($username, $password)) {
                    echo 'data: Not connected either username or password is incorrect';
                    /** use to stream the data */
                    ob_flush();
                    flush();
                    echo "\n\n";
                    /** */
                    return;
                }
                if (isset($products)) {
                    foreach ($products as $product) {
                        $product_version = $product->versions()->latest()->first();
                        //uploading files using sftp
                        if ($product_version->files->count() ?? false) {
                            foreach ($product_version->files as $file) {
                                $message = InstallSystemService::uploadFiles($sftp, $file, $system->operating_system);
                                echo 'data:' . $message['message'] . ' for product ' . $product->name;
                                /** use to stream the data */
                                ob_flush();
                                flush();
                                echo "\n\n";
                            }
                        } else {
                            echo 'data:' . 'No file found for product ' . $product->name;
                            /** use to stream the data */
                            ob_flush();
                            flush();
                            echo "\n\n";
                        }
                        //executing environment variables
                        if ($product_version->productParameters->count() ?? false) {
                            foreach ($product_version->productParameters as $param) {
                                $message = InstallSystemService::executeProductParams($ssh, $param);
                                echo 'data:' . $message['message'] . ' for product ' . $product->name;
                                /** use to stream the data */
                                ob_flush();
                                flush();
                                echo "\n\n";
                            }
                        } else {
                            echo 'data:' . 'No params found for product ' . $product->name;
                            /** use to stream the data */
                            ob_flush();
                            flush();
                            echo "\n\n";
                        }
                        $message = InstallSystemService::executeSystemCommands($ssh, $product_version, $system);
                        echo 'data:' . $message['message']  . ' for product ' . $product->name;
                        /** use to stream the data */
                        ob_flush();
                        flush();
                        echo "\n\n";
                        //already installed systems
                        $installed_ids =  $system->installedProducts->pluck('id');
                        if (!$installed_ids->contains($product->id)) {
                            //installing new system
                            $system->installedProducts()->attach($product->id, ['quantity' => $product->pivot->quantity]);
                        }
                    }
                }
            } catch (Exception $e) {
                echo 'data: An exception has occured while executing ' . $e->getMessage();
                /**use to stream the data */
                ob_flush();
                flush();
                echo "\n\n";
                /** */
                return;
            }
            return;
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
        return response()->json(['message' => 'Commands successfully Executed.'], 200);
    }

    /**
     * execute custom commands
     * @param  object $system
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function executeCustomCommands(System $system, Request $request)
    {
        return InstallSystemService::executeCustomCommands($system, $request);
    }
}