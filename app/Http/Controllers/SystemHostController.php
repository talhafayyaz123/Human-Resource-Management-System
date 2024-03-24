<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Models\SystemHost;
use App\Utils\Logger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Request as staticRequest;

class SystemHostController extends Controller
{
    /**
     * Runs when instance is initiated
     */

    use CustomHelper;
    public function __construct()
    {
        $this->middleware('check.permission:system-host.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:system-host.create', ['only' => ['store']]);
        $this->middleware('check.permission:system-host.edit', ['only' => ['update']]);
        $this->middleware('check.permission:system-host.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new SystemHost;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $hosts = $model->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($host) => [
                'id' => $host->id,
                'port' => $host->server_port,
                'username' => $host->username,
                'serverIp' => $host->server_ip,
                'password' => $host->password,
                'machineName' => $host->machine_name,
                'createdAt' => Carbon::parse($host->created_at)->format('d/m/y'),
            ]);
        return response()->json(['hosts' => $hosts]);
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
            "port" => "required|string",
            "password" => "required|string",
            "serverIp" => "required|string",
            "username" => "required|string",
            "machineName" => "required|string"
        ]);

        //Create Product Host
        $model = new SystemHost;
        $model->server_ip = $request->serverIp;
        $model->server_port = $request->port;
        $model->password = $request->password;
        $model->username = $request->username;
        $model->machine_name = $request->machineName;
        $model->save();
        $content = [
            'moduleAction' => 'SystemHostCreate',
            'data' => $model->toArray()
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Supplier host'])], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $host = SystemHost::where("id", $id)->first();
        return response()->json(['host' => [
            'id' => $host->id,
            'port' => $host->server_port,
            'username' => $host->username,
            'serverIp' => $host->server_ip,
            'password' => $host->password,
            'machineName' => $host->machine_name,
        ]]);
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
            "port" => "required|string",
            "password" => "required|string",
            "serverIp" => "required|string",
            "username" => "required|string",
            "machineName" => "required|string"
        ]);

        $model = SystemHost::where(["id" => $id])->first();
        $model->server_ip = $request->serverIp;
        $model->server_port = $request->port;
        $model->password = $request->password;
        $model->username = $request->username;
        $model->machine_name = $request->machineName;
        $model->save();
        $content = [
            'moduleAction' => 'UpdateHostCreate',
            'data' => $model->toArray()
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model =  SystemHost::where('id', $id)->delete();
        $content = [
            'moduleAction' => 'DeleteHostCreate',
            'data' => $model->toArray()
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }
}