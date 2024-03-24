<?php

namespace App\Http\Controllers;

use App\Models\CloudInfrastructre;
use App\Models\CloudInfrastructureServer;
use Illuminate\Http\Request;

class CloudInfrastructureServerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'clusterId' => 'required',
            'isMaster' => 'required|boolean',
            'serverPoolId' => 'required'
        ]);
        $cloud_infrastructure = new CloudInfrastructureServer();
        $cloud_infrastructure->cluster_id = $request->clusterId;
        $cloud_infrastructure->is_master = $request->isMaster;
        $cloud_infrastructure->server_pool_id = $request->serverPoolId;
        $cloud_infrastructure->save();
        return response()->json(['message' => 'Cloud server is saved']);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'serverPoolId' => 'required',
            'clusterId' => 'required'
        ]);
        CloudInfrastructureServer::where('server_pool_id', $request->serverPoolId)
            ->where('cluster_id', $request->clusterId)
            ->delete();
        return response()->json(['message' => 'Cloud server is saved']);
    }

    public function show($id)
    {
        $cloud_server = CloudInfrastructre::findOrFail($id);
        $server = $cloud_server->cloudServers()->where('is_master', true)->first();
        if (empty($server)) {
            return response()->json(['message' => 'no server with master exists'], 404);
        }
        return response()->json(['data' => $server]);
    }
}
