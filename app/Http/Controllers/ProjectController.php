<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Models\Project;
use App\Models\Task;
use App\Utils\Logger;
use Illuminate\Http\Request;
use App\Models\GlobalSetting;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as staticRequest;

class ProjectController extends Controller
{
    use CustomHelper;

    /**
     * Runs when instance is initiated
     */
    public function __construct()
    {
        $this->middleware('check.permission:project.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:project.create', ['only' => ['store']]);
        $this->middleware('check.permission:project.edit', ['only' => ['update']]);
        $this->middleware('check.permission:project.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;

        $model = new Project;

        if ($request->companyId) {
            $model = $model->where("company_id", $request->companyId);
        }

        if ($request->userId) {
            $model = $model->where("user_id", $request->userId);
        }

        if ($request->isInternalProject) {
            $model = $model->where("is_internal_project", $request->isInternalProject);
        }

        if (is_array($request->status)) {
            $model = $model->whereIn('status', $request->status);
        }

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');

        $models = $model->filter(staticRequest::only('search'))->paginate($per_page);



        $model_collect = $models->map(function ($item) {
            $milestones = $item->milestones->pluck('id')->toArray();
            $tasks = Task::whereIn('milestone_id', $milestones)->get();
            $taskCount = $tasks->count();
            $completedTask = $tasks->where('status', 'done')->count();
            return [
                'id' => $item->id,
                'projectNumber' => $item->project_number,
                'name' => $item->name,
                'status' => $item->status,
                "plannedStartDate" => $item->planned_start_date,
                "actualStartDate" => $item->actual_start_date,
                "earliestStartDate" => $item->earliest_start_date,
                "actualFinishedDate" => $item->actual_finished_date,
                "expectedFinishedDate" => $item->expected_finished_date,
                "plannedFinishedDate" => $item->planned_finished_date,
                "estimatedTime" => $item->estimated_time,
                "neededTime" => $item->needed_time,
                "neededTimeWithGoodwill" => $item->needed_time_with_goodwill,
                "companyId" => $item->company_id,
                'companyName' => $item?->company?->company_name,
                "userId" => $item->user_id,
                "description" => $item->description,
                "milestoneCount" => $item->milestones->count(),
                "taskCount" => $taskCount,
                "completedTasks" => $completedTask,
            ];
        });
        if ($sort_by && $sort_order) {
            $model_collect = $this->applySorting($model_collect, $sort_by, $sort_order);
        }
        return response()->json([
            'data' => $model_collect,
            'links' => $models->links(),
            'meta' => [
                'current_page' => $models->currentPage(),
                'from' => $models->firstItem(),
                'last_page' => $models->lastPage(),
                'path' => request()->url(),
                'per_page' => $models->perPage(),
                'to' => $models->lastItem(),
                'total' => $models->total(),
            ]
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
            "name" => "required",
            "status" => "required",
            "companyId" => "required",
            "employeeId" => "required",
            'internalProject' => 'required|boolean',
            'orderConfirmationId' => 'nullable'
        ]);
       
        //Create Project Group
        $model = new Project;
        $saveData = $this->saveData($model, $request);
        $content = [
            'moduleAction' => 'createProject',
            'data' => $saveData->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Project'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Project::where('id', $id)->first();
        return response()->json([
            'projects' => [
                'id' => $model->id,
                "projectNumber" => $model->project_number,
                "plannedStartDate" => $model->planned_start_date,
                "actualStartDate" => $model->actual_start_date,
                "earliestStartDate" => $model->earliest_start_date,
                "actualFinishedDate" => $model->actual_finished_date,
                "expectedFinishedDate" => $model->expected_finished_date,
                "plannedFinishedDate" => $model->planned_finished_date,
                "name" => $model->name,
                "description" => $model->description,
                "estimatedTime" => $model->estimated_time,
                "neededTime" => $model->needed_time,
                "neededTimeWithGoodwill" => $model->needed_time_with_goodwill,
                "externalOrderNumber" => $model->external_order_number,
                "status" => $model->status,
                "comments" => $model->comments,
                "companyId" => $model->company ? [
                    'id' => $model->company?->id,
                    'companyName' => $model->company?->company_name,
                ] : null,
                "employeeId" => $model->user_id,
                'internalProject' => $model->is_internal_project ? true : false,
                'orderConfirmations' => $model->orderConfirmations?->map(function ($order){
                    return [
                        'id' => $order->id,
                        'saleOfferNumber' => $order->sale_offer_number,
                    ];
                }),
                'assignedUsers' => $model->assignedUsers->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'userId' => $user->user_id,
                        'employee' => $user->full_name
                    ];
                })
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
            "name" => "required",
            "status" => "required",
            "companyId" => "required",
            "employeeId" => "required",
        ]);

        //Create Project
        $model = Project::where("id", $id)->first();
        $saveData = $this->saveData($model, $request);
        $content = [
            'moduleAction' => 'updateProject',
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
        if (!$model->project_number) {
            $global_survey_setting = GlobalSetting::where('key', 'project')->where('year', date("Y"))->first();
            if (empty($global_survey_setting)) {
                $global_setting = new GlobalSetting;
                $global_setting->key = 'project';
                $global_setting->value = '0001';
                $global_setting->year = date("Y");
                $global_setting->save();
            } else {
                $global_setting = tap(DB::table('global_settings')->where('key', 'project')->where('year', date("Y")))->update([
                    'value' => DB::raw("value+1")
                ])->first();
            }
            $model->project_number = 'P' . date("Y") . '-' . $global_setting->value;
        }
        $model->planned_start_date = $request->plannedStartDate;
        $model->actual_start_date = $request->actualStartDate;
        $model->earliest_start_date = $request->earliestStartDate;
        $model->actual_finished_date = $request->actualFinishedDate;
        $model->expected_finished_date = $request->expectedFinishedDate;
        $model->planned_finished_date = $request->plannedFinishedDate;
        $model->name = $request->name;
        $model->description = $request->description;
        $model->estimated_time = $request->estimatedTime;
        $model->needed_time = $request->neededTime;
        $model->status = $request->status;
        $model->comments = $request->comments;
        $model->is_internal_project = $request->internalProject ?? null;
        $model->company_id = $request->companyId;
        $model->user_id = $request->employeeId;
        $model->external_order_number = $request->externalOrderNumber ?? null;
        $model->save();

        $model->orderConfirmations()->sync($request->orderConfirmationIds);
        $model->assignedUsers()->sync($request->assignedUsers);
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
        $model = Project::find($id);
        $model->delete();
        $content = [
            'moduleAction' => 'deleteProject',
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
        $model = Project::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }

    /**
     * Display a listing of the resource.
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function getProjectByCompany(Request $request)
    {
        $model = new Project;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->where("company_id", $request->id)->orderBy('created_at')->get();
        $data = $model->map(function ($item) {
            return [
                'id' => $item->id,
                'projectNumber' => $item->project_number,
                'name' => $item->name,
                'status' => $item->status,
                "plannedStartDate" => $item->planned_start_date,
                "actualStartDate" => $item->actual_start_date,
                "earliestStartDate" => $item->earliest_start_date,
                "actualFinishedDate" => $item->actual_finished_date,
                "expectedFinishedDate" => $item->expected_finished_date,
                "plannedFinishedDate" => $item->planned_finished_date,
                "estimatedTime" => $item->estimated_time,
                "neededTime" => $item->needed_time,
                "neededTimeWithGoodwill" => $item->needed_time_with_goodwill,
            ];
        });
        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }
}
