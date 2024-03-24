<?php

namespace App\Http\Controllers;

use App\Models\EloVersion;
use App\Utils\Logger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Request as staticRequest;

class EloVersionController extends Controller
{

    /**
     * Runs when instance is initiated
     */ 
    use CustomHelper;
    public function __construct()
    {
        $this->middleware('check.permission:elo-version.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:elo-version.create', ['only' => ['store']]);
        $this->middleware('check.permission:elo-version.edit', ['only' => ['update']]);
        $this->middleware('check.permission:elo-version.delete', ['only' => ['destroy']]);
    }

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
        $versions = new EloVersion();
        if ($sort_by && $sort_order) {
            $versions = $this->applySortingBeforePagination($versions, $sort_by, $sort_order);
        }
        $versions = $versions->orderBy('elo_versions.name', "ASC")
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($version) => [
                'id' => $version->id,
                'name' => $version->name,
                'type' => $version->type,
                'software' => $version->software?->name ?? "-",
                'createdAt' => Carbon::parse($version->created_at)->toDateString(),
            ]);
        return response()->json(['versions' => $versions]);
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
            "name" => "required|string",
            'type' => "required",
            'softwareId' => 'required'
        ]);

        //Create Elo version Group
        $model = new EloVersion();

        $model->name = $request->name;
        $model->type = $request->type;
        $model->software_id = $request->softwareId;
        $model->save();
        return response()->json(['message' => 'Record created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $version = EloVersion::where("id", $id)->first();
        return response()->json(['version' => [
            'id' => $version->id,
            "name" => $version->name,
            "type" => $version->type,
            "softwareId" => $version->software_id,
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
            "name" => "required",
            "type" => "required",
            'softwareId' => 'required'
        ]);
        $model = EloVersion::findOrFail($id);
        $model->name = $request->name;
        $model->type = $request->type;
        $model->software_id = $request->softwareId;
        $model->save();
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
        $elo_version = EloVersion::findOrFail($id);
        $elo_version->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }

    public function getVersionsBySoftware($id)
    {
        $versions = EloVersion::where('software_id', $id)->get();
        return $versions;
    }
}
