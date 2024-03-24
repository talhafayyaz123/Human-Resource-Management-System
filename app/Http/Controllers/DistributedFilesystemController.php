<?php

namespace App\Http\Controllers;

use App\Models\DistributedFilesystem;
use App\Models\DistributedFilesystemServer;
use App\Utils\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomHelper;

class DistributedFilesystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use CustomHelper;

    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new DistributedFilesystem;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $distributed_filesystem = $model->paginate($per_page)->withQueryString()->map(fn ($item) => [
            "id" => $item->id,
            "name" => $item->name,
            "subType" => $item->sub_type,
            "instanceType" => $item->instance_type,
        ]);
        return [
            "data" => $distributed_filesystem
        ];
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
            "name" => "required",
            "subType" => "required|string",
            "instanceType" => "required",
            'cloudServers.*.serverIp' => 'nullable|string',
            'cloudServers.*.port' => 'nullable|string',
            'cloudServers.*.userAddress' => 'nullable|string',
            'cloudServers.*.password' => 'nullable|string',
            'cloudServers.*.isMaster' => 'nullable|boolean',
        ]);
        $distributed_filesystem = new DistributedFilesystem();
        $this->saveData($distributed_filesystem, $request);
        return response()->json(['message' => 'Record created successfully.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = DistributedFilesystem::where('id', $id)->firstOrFail();
        return response()->json([
            'distributedFilesystem' => [
                'id' => $model->id,
                "name" => $model->name,
                'subType' => $model->sub_type,
                "instanceType" => $model->instance_type,
                'cloudServers' => !empty($model->servers) ? $model->servers->map(function ($server) {
                    return [
                        'id' => $server->id,
                        'serverIp' => $server->server_ip,
                        'port' => $server->server_port,
                        'userAddress' => $server->username,
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
            "subType" => "required|string",
            "instanceType" => "required",
            "name" => "required|string",
            'cloudServers.*.serverIp' => 'nullable|string',
            'cloudServers.*.port' => 'nullable|string',
            'cloudServers.*.userAddress' => 'nullable|string',
            'cloudServers.*.password' => 'nullable|string',
            'cloudServers.*.isMaster' => 'nullable|boolean',
        ]);
        $distributed_filesystem = DistributedFilesystem::findOrFail($id);
        $this->saveData($distributed_filesystem, $request);
        return response()->json(['message' => 'Record updated.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $distributed_filesystem = DistributedFilesystem::findOrFail($id);
        $distributed_filesystem->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    /**
     * Save database cloud and cloud server data to the database.
     *
     * @param DistributedFilesystem $distributed_filesystem
     * @param Request $request
     * @return DistributedFilesystem
     */
    private function saveData(DistributedFilesystem $distributed_filesystem, Request $request): DistributedFilesystem
    {
        return DB::transaction(function () use ($distributed_filesystem, $request) {
            // Set system properties from the request
            $distributed_filesystem->instance_type = $request->instanceType;
            $distributed_filesystem->name = $request->name;
            $distributed_filesystem->sub_type = $request->subType;
            // Save the system to the database
            $distributed_filesystem->save();

            if (!empty($distributed_filesystem->servers)) {
                $distributed_filesystem->servers()->delete();
            }

            // If there are cloud servers in the request, save them to the database
            if (!empty($request->cloudServers)) {
                foreach ($request->cloudServers as $server) {
                    $cloud_servers = new DistributedFilesystemServer();
                    $cloud_servers->server_ip = $server['serverIp'];
                    $cloud_servers->server_port = $server['port'];
                    $cloud_servers->username = $server['userAddress'];
                    $cloud_servers->password = $server['password'];
                    $cloud_servers->is_master = $server['isMaster'];
                    $cloud_servers->DistributedFilesystem()->associate($distributed_filesystem);
                    $cloud_servers->save();
                }
            }
            // Return the saved system
            return $distributed_filesystem;
        });
    }
}