<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Models\Milestone;
use App\Models\Project;
use App\Utils\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MilestoneController extends Controller
{
    /**
     * Runs when instance is initiated
     */
    public function __construct()
    {
        $this->middleware('check.permission:milestone.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:milestone.create', ['only' => ['store']]);
        $this->middleware('check.permission:milestone.edit', ['only' => ['update']]);
        $this->middleware('check.permission:milestone.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 10;

        $model = new Milestone;
        $models = $model->paginate($per_page);
        $paginate_data = $models->toArray();

        $model_collect = $models->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'status' => $item->status,
                'projectId' => $item->project_id,
            ];
        });
        return response()->json([
            'data' => $model_collect,
            'links' => $paginate_data['links'],
            'count' => $paginate_data['total'],
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
            "milestoneId" => "required",
            "name" => "required",
            "status" => "required",
            "plannedStartDate" => "required"
        ]);

        //Create Milestone Group
        $model = new Milestone;
        $saveData = $this->saveData($model, $request);
        $content = [
            'moduleAction' => 'createProjectMilestone',
            'data' => $saveData->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
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
        $model = Milestone::where('id', $id)->first();

        return response()->json([
            'milestones' => [
                'id' => $model->id,
                "milestoneId" => $model->milestone_id,
                "plannedStartDate" => $model->planned_start_date,
                "actualStartDate" => $model->actual_start_date,
                "earliestStartDate" => $model->earliest_start_date,
                "actualFinishedDate" => $model->actual_finished_date,
                "expectedFinishedDate" => $model->expected_finished_date,
                "plannedFinishedDate" => $model->planned_finished_date,
                "name" => $model->name,
                "description" => $model->description,
                "status" => $model->status,
                "comments" => $model->comments,
                "projectId" => $model->project_id,
            ],
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
            "milestoneId" => "required",
            "name" => "required",
            "status" => "required",
            "plannedStartDate" => "required"
        ]);

        //Create Milestone Group
        $model = Milestone::where("id", $id)->first();
        $saveData = $this->saveData($model, $request);
        $content = [
            'moduleAction' => 'updateProjectMilestone',
            'data' => $saveData->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }


    /**
     * Saves the data based on provided resource item
     *
     * @param   object  $model
     * @param   object  $request
     * @param   array   Response
     */
    public function saveData($model, $request)
    {
        $model->milestone_id = $request->milestoneId;
        $model->planned_start_date = $request->plannedStartDate;
        $model->actual_start_date = $request->actualStartDate;
        $model->earliest_start_date = $request->earliestStartDate;
        $model->actual_finished_date = $request->actualFinishedDate;
        $model->expected_finished_date = $request->expectedFinishedDate;
        $model->planned_finished_date = $request->plannedFinishedDate;
        $model->name = $request->name;
        $model->description = $request->description;
        $model->status = $request->status;
        $model->comments = $request->comments;

        $model->project_id = $request->projectId;
        $model->save();
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
        $model = Milestone::find($id);
        $model->delete();
        $content = [
            'moduleAction' => 'deleteProjectMilestone',
            'data' => $model->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
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
        $model = Milestone::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }

    /**
     * Get milestones pagination on base of project id
     *
     * @param  int $projectId
     * @return \Illuminate\Http\Response
     */
    public function getMilestonesByProject($projectId)
    {
        return response()->json([
            'milestones' => Milestone::where('project_id', $projectId)->orderBy('created_at')
                ->paginate(25)
                ->withQueryString()
                ->through(fn ($item) => [
                    'id' => $item->id,
                    'milestoneId' => $item->milestone_id,
                    "plannedStartDate" => $item->planned_start_date,
                    "actualStartDate" => $item->actual_start_date,
                    "earliestStartDate" => $item->earliest_start_date,
                    "actualFinishedDate" => $item->actual_finished_date,
                    "expectedFinishedDate" => $item->expected_finished_date,
                    "plannedFinishedDate" => $item->planned_finished_date,
                    "name" => $item->name,
                    "description" => $item->description,
                    "status" => $item->status,
                ]),
        ]);
    }
}
