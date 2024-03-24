<?php

namespace App\Http\Controllers;

use App\Models\DatabaseCloud;
use App\Models\DatabaseCloudServer;
use App\Utils\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomHelper;

class DatabaseCloudController extends Controller
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
        $model = new DatabaseCloud;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $database_cloud = $model->paginate($per_page)->withQueryString()->map(fn ($item) => [
            "id" => $item->id,
            "name" => $item->name,
            "subType" => $item->sub_type,
            "instanceType" => $item->instance_type,
            "databaseType" => $item->database_type
        ]);
        return [
            "data" => $database_cloud
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
            "databaseType" => "required|string",
            'cloudServers.*.serverIp' => 'nullable|string',
            'cloudServers.*.port' => 'nullable|string',
            'cloudServers.*.userAddress' => 'nullable|string',
            'cloudServers.*.password' => 'nullable|string',
        ]);
        $database_cloud = new DatabaseCloud();
        $this->saveData($database_cloud, $request);
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
        $model = DatabaseCloud::where('id', $id)->firstOrFail();
        return response()->json([
            'databaseCloud' => [
                'id' => $model->id,
                "name" => $model->name,
                'subType' => $model->sub_type,
                "instanceType" => $model->instance_type,
                "databaseType" => $model->database_type,
                'cloudServers' => !empty($model->servers) ? $model->servers->map(function ($server) {
                    return [
                        'id' => $server->id,
                        'serverIp' => $server->server_ip,
                        'port' => $server->server_port,
                        'userAddress' => $server->username,
                        'password' => $server->password,
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
            "databaseType" => "required",
            "subType" => "required|string",
            "instanceType" => "required",
            "name" => "required|string",
            'cloudServers.*.serverIp' => 'nullable|string',
            'cloudServers.*.port' => 'nullable|string',
            'cloudServers.*.userAddress' => 'nullable|string',
            'cloudServers.*.password' => 'nullable|string',
        ]);
        $database_cloud = DatabaseCloud::findOrFail($id);
        $this->saveData($database_cloud, $request);
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
        $database_cloud = DatabaseCloud::findOrFail($id);
        $database_cloud->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    /**
     * Save database cloud and cloud server data to the database.
     *
     * @param DatabaseCloud $database_cloud
     * @param Request $request
     * @return DatabaseCloud
     */
    private function saveData(DatabaseCloud $database_cloud, Request $request): DatabaseCloud
    {
        return DB::transaction(function () use ($database_cloud, $request) {
            // Set system properties from the request
            $database_cloud->database_type = $request->databaseType;
            $database_cloud->instance_type = $request->instanceType;
            $database_cloud->name = $request->name;
            $database_cloud->sub_type = $request->subType;
            // Save the system to the database
            $database_cloud->save();

            if (!empty($database_cloud->servers)) {
                $database_cloud->servers()->delete();
            }

            // If there are cloud servers in the request, save them to the database
            if (!empty($request->cloudServers)) {
                foreach ($request->cloudServers as $server) {
                    $cloud_servers = new DatabaseCloudServer();
                    $cloud_servers->server_ip = $server['serverIp'];
                    $cloud_servers->server_port = $server['port'];
                    $cloud_servers->username = $server['userAddress'];
                    $cloud_servers->password = $server['password'];
                    $cloud_servers->databaseCloud()->associate($database_cloud);
                    $cloud_servers->save();
                }
            }
            // Return the saved system
            return $database_cloud;
        });
    }
}