<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Models\Milestone;
use App\Models\Task;
use App\Traits\CustomHelper;
use App\Utils\Logger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use CustomHelper;

    /**
     * Runs when instance is initiated
     */
    public function __construct()
    {
        $this->middleware('check.permission:task.list', ['only' => ['index', 'show', 'getTasksByDate']]);
        $this->middleware('check.permission:task.create', ['only' => ['store']]);
        $this->middleware('check.permission:task.edit', ['only' => ['update']]);
        $this->middleware('check.permission:task.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage;
        $task = Task::orderBy('created_at');
        if ($request->has('projectId') && strlen($request->projectId)) {
            $task->whereHas('milestone', function ($q) use ($request) {
                $q->where('project_id', '=', $request->projectId);
            });
        }
        if ($request->has('status') && strlen($request->status)) {
            $task->where('status', $request->status);
        }
        if ($request->has('priority') && strlen($request->priority)) {
            $task->where('priority', $request->priority);
        }
        if ($request->has('milestoneId') && strlen($request->milestoneId)) {
            $task->where('milestone_id', $request->milestoneId);
        }
        if ($request->has('employeeId') && strlen($request->employeeId)) {
            $task->where('employee_id', $request->employeeId);
        }

        return response()->json([
            'tasks' => $task
                ->paginate($per_page)
                ->withQueryString()
                ->through(fn ($item) => [
                    'id' => $item->id,
                    "taskId" => $item->task_id,
                    "companyId" => $item->milestone?->project?->company_id,
                    "plannedStartDate" => $item->planned_start_date,
                    "actualStartDate" => $item->actual_start_date,
                    "earliestStartDate" => $item->earliest_start_date,
                    "actualFinishedDate" => $item->actual_finished_date,
                    "expectedFinishedDate" => $item->expected_finished_date,
                    "plannedFinishedDate" => $item->planned_finished_date,
                    "spentTime" => $item->spent_time,
                    "estimatedTime" => $item->estimated_time,
                    "name" => $item->name,
                    "description" => $item->description,
                    "status" => $item->status,
                    "comments" => $item->comments,
                    "milestoneId" => $item->milestone_id,
                    "employeeId" => $item->employee_id,
                    "priority" => $item->priority,
                    "taskHours" => $item->task_hours,
                    "relationships" => $item->relationships,
                ]),
            'milestones' => Milestone::where('project_id', '=', $request->projectId)->orderBy('name')
                ->get()
                ->map(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                ]),
        ]);
    }

    /**
     * check backlog tasks assigned to no one
     * @return \Illuminate\Http\Response
     */
    public function backlog()
    {
        return response()->json([
            'backlog' => Task::whereNull('employee_id')->orderBy('created_at')
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($item) => [
                    'id' => $item->id,
                    "taskId" => $item->task_id,
                    "plannedStartDate" => $item->planned_start_date,
                    "actualStartDate" => $item->actual_start_date,
                    "earliestStartDate" => $item->earliest_start_date,
                    "actualFinishedDate" => $item->actual_finished_date,
                    "expectedFinishedDate" => $item->expected_finished_date,
                    "plannedFinishedDate" => $item->planned_finished_date,
                    "spentTime" => $item->spent_time,
                    "estimatedTime" => $item->estimated_time,
                    "name" => $item->name,
                    "description" => $item->description,
                    "status" => $item->status,
                    "comments" => $item->comments,
                    "milestoneId" => $item->milestone_id,
                    "employeeId" => $item->employee_id,
                    "priority" => $item->priority,
                    "taskHours" => $item->task_hours,
                    "relationships" => $item->relationships,
                ]),
            'milestones' => Milestone::orderBy('name')
                ->get()
                ->map(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                ]),
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
            "taskId" => "required",
            "name" => "required",
            "status" => "required",
            "priority" => "required",
        ]);

        if (!$request->fromMyTasks) {
            $request->validate([
                "milestoneId" => "required",
            ]);
        } else {
            $request->validate([
                "employeeId" => "required",
            ]);
        }

        //Create Task Group
        $model = new Task;
        $saveData = $this->saveData($model, $request);
        $content = [
            'moduleAction' => 'createMilestoneTask',
            'data' => $saveData->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['success' => true, 'message' => trans('messages.record_saved_success', ['name' => 'Task'])]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Task::find($id);
        return response()->json(["tasks" => [
            'id' => $model->id,
            "taskId" => $model->task_id,
            "plannedStartDate" => $model->planned_start_date,
            "actualStartDate" => $model->actual_start_date,
            "earliestStartDate" => $model->earliest_start_date,
            "actualFinishedDate" => $model->actual_finished_date,
            "expectedFinishedDate" => $model->expected_finished_date,
            "plannedFinishedDate" => $model->planned_finished_date,
            "spentTime" => $model->spent_time,
            "estimatedTime" => $model->estimated_time,
            "name" => $model->name,
            "description" => $model->description,
            "taskHours" => $model->task_hours,
            "status" => $model->status,
            "comments" => $model->comments,
            "projectId" => $model->project_id,
            "relationships" => $model->relationships,
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
            "taskId" => "required",
            "name" => "required",
            "status" => "required",
            "priority" => "required",
        ]);

        if (!$request->fromMyTasks) {
            $request->validate([
                "milestoneId" => "required",
            ]);
        } else {
            $request->validate([
                "employeeId" => "required",
            ]);
        }

        //Create Task Group
        $model = Task::where("id", $id)->first();
        $saveData = $this->saveData($model, $request);
        $content = [
            'moduleAction' => 'updateMilestoneTask',
            'data' => $saveData->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['success' => true, 'message' => 'Task has been updated.']);
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
        $model->task_id = $request->taskId;
        $model->planned_start_date = $request->plannedStartDate;
        $model->actual_start_date = $request->actualStartDate;
        $model->earliest_start_date = $request->earliestStartDate;
        $model->actual_finished_date = $request->actualFinishedDate;
        $model->expected_finished_date = $request->expectedFinishedDate;
        $model->planned_finished_date = $request->plannedFinishedDate;
        $model->name = $request->name;
        $model->description = $request->description;
        $model->estimated_time = $request->estimatedTime;
        $model->spent_time = $request->spentTime;
        $model->status = $request->status;
        $model->priority = $request->priority;
        $model->comments = $request->comments;
        $model->employee_id = $request->employeeId;
        $model->task_hours = $request->taskHours;
        $model->relationships = $request->relationships;

        $model->milestone_id = $request->milestoneId ?? null;
        $model->resubmit_on = Carbon::now();
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
        $model = Task::find($id);
        $model->delete();
        $content = [
            'moduleAction' => 'deleteMilestoneTask',
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
        $model = Task::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }

    /**
     * Get all tasks of the given user by date
     * @param \Illuminate\Http\Request
     * @param \Illuminate\Http\Response
     */
    public function getTasksByDate(Request $request)
    {
        $model = new Task;

        $model_collect = $model->whereDate("created_at", 'like', $request->date)
            ->whereHas("milestone.project", function ($query) use ($request) {
                if (!$this->hasRole($request, 'admin')) {
                    $query->where('user_id', $this->getAuthUserId($request));
                }
            })->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                ];
            });

        return response()->json([
            'tasks' => $model_collect,
        ]);
    }
}
