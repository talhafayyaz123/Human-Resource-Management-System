<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use Illuminate\Http\Request;
use App\Models\ProjectProtocolDescriptionEntry;
use Carbon\Carbon;

class ProjectProtocolDescriptionEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of resource based on foreign key
     * @param Request
     * @param $id project_protocol_id
     * @return \Illuminate\Http\Response
     */
    public function getEntriesByProjectProtocol(Request $request, $id)
    {
        $per_page = $request->perPage ?? 25;

        $entries = ProjectProtocolDescriptionEntry::where('project_protocol_id', $id)->orderBy('created_at')->paginate($per_page)
            ->withQueryString()
            ->through(fn ($entry) => [
                "id" => $entry->id,
                "description" => $entry->description,
                "expiryDate" => Carbon::parse($entry->expiry_date)->toDateString(),
                "ownership" => $entry->ownership,
            ]);

        return response()->json([
            'data' => $entries
        ]);
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
            'description' => 'required|string',
            'expiryDate' => 'required',
            'ownership' => 'required|string',
            'projectProtocolId' => 'required|integer'
        ]);

        $entry = new ProjectProtocolDescriptionEntry();
        $entry->description = $request->description;
        $entry->expiry_date = Carbon::parse($request->expiryDate);
        $entry->ownership = $request->ownership;
        $entry->project_protocol_id = $request->projectProtocolId;
        $entry->save();

        $content = [
            'moduleAction' => 'createProjectProtocolEntry',
            'data' => $entry->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json([
            "success" => true,
            "message" => "Record Created Successfully"
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
        $entry = ProjectProtocolDescriptionEntry::findOrFail($id);

        return response()->json([
            "id" => $entry->id,
            "description" => $entry->description,
            "expiryDate" => Carbon::parse($entry->expiryDate)->toDateString(),
            "ownership" => $entry->ownership,
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
        $entry = ProjectProtocolDescriptionEntry::findOrFail($id);

        $request->validate([
            'description' => 'required|string',
            'expiryDate' => 'required',
            'ownership' => 'required|string',
        ]);

        $entry->description = $request->description;
        $entry->expiry_date = Carbon::parse($request->expiryDate);
        $entry->ownership = $request->ownership;
        $entry->save();

        $content = [
            'moduleAction' => 'updateProjectProtocolEntry',
            'data' => $entry->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json([
            "success" => true,
            "message" => "Record Updated Successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entry = ProjectProtocolDescriptionEntry::findOrFail($id);
        $entry->delete();

        $content = [
            'moduleAction' => 'deleteProjectProtocolEntry',
            'data' => $entry->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json([
            "success" => true,
            "message" => "Record Deleted Successfully"
        ]);
    }
}
