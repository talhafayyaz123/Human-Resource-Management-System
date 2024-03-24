<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Services\ProjectProtocolService;
use App\Http\Requests\ProjectProtocolRequest;
use App\Http\Resources\ProjectProtocolResource;
use App\Models\ProjectProtocol;

use App\Traits\CustomHelper;
use Illuminate\Http\Request;

class ProjectProtocolController extends Controller
{
    use CustomHelper;

    public ProjectProtocolService $projectProtocolService;

    public function __construct(ProjectProtocolService $projectProtocolService)
    {
        $this->middleware('check.permission:project-protocols.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:project-protocols.create', ['only' => ['store']]);
        $this->middleware('check.permission:project-protocols.edit', ['only' => ['update']]);
        $this->middleware('check.permission:project-protocols.delete', ['only' => ['destroy']]);
        $this->projectProtocolService = $projectProtocolService;
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
        $model = new ProjectProtocol();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        // Paginate the ProjectProtocol records
        $project_protocols = $model->paginate($per_page);
        // Return the paginated ProjectProtocolResource
        return ProjectProtocolResource::collection($project_protocols);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectProtocolRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $project_protocol = $this->projectProtocolService->createProjectProtocol($data);
        $content = [
            'moduleAction' => 'createProjectProtocol',
            'data' => $project_protocol->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json([
            "success" => true,
            "message" => "Record created successfully.",
            "id" => $project_protocol->id
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
        $project_protocol = ProjectProtocol::findOrFail($id);
        return new ProjectProtocolResource($project_protocol);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectProtocolRequest $request, $id)
    {
        $project_protocol = ProjectProtocol::findOrFail($id);
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $project_protocol = $this->projectProtocolService->updateProjectProtocol($project_protocol, $data);
        $content = [
            'moduleAction' => 'updateProjectProtocol',
            'data' => $project_protocol->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json([
            "success" => true,
            "message" => "Record updated successfully."
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
        $project_protocol = ProjectProtocol::findOrFail($id);
        $this->projectProtocolService->deleteProjectProtocol($project_protocol);
        $content = [
            'moduleAction' => 'deleteProjectProtocol',
            'data' => $project_protocol->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json([
            "success" => true,
            "message" => "Record deleted successfully."
        ]);
    }
}
