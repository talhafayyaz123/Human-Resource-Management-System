<?php

namespace App\Http\Controllers;

use App\Models\EmployeeVacationApprover;
use App\Models\Task;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\TravelExpenseApprover;
use App\Services\TaskService\TaskService;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectManagementController extends Controller
{

    use CustomHelper;
    public TaskService $taskService;

    /**
     * Runs when instance is initiated
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
        $this->middleware('check.permission:task.list', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return response()->json([
            'tasks' => Task::whereNotNull('employee_id')->whereHas('milestone', function ($q) use ($id) {
                $q->where('project_id', '=', $id);
            })->orderBy('created_at')
                ->get()
                ->map(fn ($item) => [
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
            'milestones' => Milestone::where('project_id', '=', $id)->orderBy('name')
                ->get()
                ->map(fn ($milestone) => [
                    'id' => $milestone->id,
                    "milestoneId" => $milestone->milestone_id,
                    "plannedStartDate" => $milestone->planned_start_date,
                    "actualStartDate" => $milestone->actual_start_date,
                    "earliestStartDate" => $milestone->earliest_start_date,
                    "actualFinishedDate" => $milestone->actual_finished_date,
                    "expectedFinishedDate" => $milestone->expected_finished_date,
                    "plannedFinishedDate" => $milestone->planned_finished_date,
                    "name" => $milestone->name,
                    "description" => $milestone->description,
                    "status" => $milestone->status,
                    "comments" => $milestone->comments,
                    "projectId" => $milestone->project_id,
                ]),
        ]);
    }

    /**
     * Get tasks for the given user
     */
    public function getMyTasks(Request $request)
    {
        $this->taskService->createTaskForDriverLicenceCheck($request);
        $this->taskService->createTaskBeforeEndOfLease($request);
        $this->taskService->createTaskForFuelMonitoring($request);
        $tasks = Task::where('employee_id', $this->getAuthUserId($request))
            ->where('resubmit_on', '<=', Carbon::now())
            ->when(isset($request->milestone), function ($query) use ($request) {
                $query->whereHas('milestone', function ($query) use ($request) {
                    $query->where('id', $request->milestone);
                });
            })
            ->when(isset($request->status), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when(isset($request->project), function ($query) use ($request) {
                $query->whereHas('milestone.project', function ($query) use ($request) {
                    $query->where('id', $request->project);
                });
            })->orderBy('created_at');
        return response()->json([
            'tasks' => $tasks->get()
                ->map(fn ($item) => [
                    'id' => $item->id,
                    "taskId" => $item->task_id,
                    "taskableType" => $item->taskable_type,
                    "taskableId" => $item->taskable_id,
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
                    "projectId" => $item->milestone?->project?->id,
                    "relationships" => $item->relationships,
                ]),
            'milestones' => Milestone::orderBy('name')->whereHas('project', function ($q) use ($request) {
                $q->where('user_id', '=', $this->getAuthUserId($request));
            })->get()
                ->map(fn ($milestone) => [
                    'id' => $milestone->id,
                    "milestoneId" => $milestone->milestone_id,
                    "plannedStartDate" => $milestone->planned_start_date,
                    "actualStartDate" => $milestone->actual_start_date,
                    "earliestStartDate" => $milestone->earliest_start_date,
                    "actualFinishedDate" => $milestone->actual_finished_date,
                    "expectedFinishedDate" => $milestone->expected_finished_date,
                    "plannedFinishedDate" => $milestone->planned_finished_date,
                    "name" => $milestone->name,
                    "description" => $milestone->description,
                    "status" => $milestone->status,
                    "comments" => $milestone->comments,
                    "projectId" => $milestone->project_id,
                ]),
            'projects' => Project::where('user_id', $this->getAuthUserId($request))->orderBy('created_at')
                ->get()
                ->map(fn ($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'status' => $item->status,
                    "plannedStartDate" => $item->planned_start_date,
                    "actualStartDate" => $item->actual_start_date,
                    "earliestStartDate" => $item->earliest_start_date,
                    "actualFinishedDate" => $item->actual_finished_date,
                    "expectedFinishedDate" => $item->expected_finished_date,
                    "plannedFinishedDate" => $item->planned_finished_date,
                    "estimatedTime" => $item->estimated_time,
                    "spentTime" => $item->spent_time,
                ]),
        ]);
    }

    /**
     * Get all tasks related to the current user
     * Get tasks assigned to the user in projects
     * Get vacation requests
     * Get travel expense requests
     */
    public function getMyTasksModified(Request $request)
    {
        $user_id = $this->getAuthUserId($request);
        $task_model = Task::where('employee_id', $user_id)
            ->where('resubmit_on', '<=', Carbon::now())
            ->when(isset($request->milestone), function ($query) use ($request) {
                $query->whereHas('milestone', function ($query) use ($request) {
                    $query->where('id', $request->milestone);
                });
            })
            ->when(isset($request->status), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when(isset($request->project), function ($query) use ($request) {
                $query->whereHas('milestone.project', function ($query) use ($request) {
                    $query->where('id', $request->project);
                });
            })->orderBy('created_at')->get();

        $task_collect = $task_model->map(function ($item) {
            return [
                'id' => $item->id,
                "name" => $item->name,
                "status" => $item->status,
                "milestone" => $item->milestone->name,
                "description" => $item->description,
                "plannedStartDate" => $item->planned_start_date,
                "plannedFinishedDate" => $item->planned_finished_date,
                "actualStartDate" => $item->actual_start_date,
                "actualFinishedDate" => $item->actual_finished_date,
                "earliestStartDate" => $item->earliest_start_date,
                "expectedFinishedDate" => $item->expected_finished_date,
                "spentTime" => $item->spent_time,
                "estimatedTime" => $item->estimated_time,
                "priority" => $item->priority,
                "project" => $item->milestone?->project?->name,
            ];
        });

        $vacation_approver_model = EmployeeVacationApprover::where("approver_id", $user_id)->with("employeeVacations", function ($query) {
            $query->where("status", "pending");
        })->get();

        $vacation_approver_collect = $vacation_approver_model->map(function ($item) {
            return [
                'id' => $item->id,
                "name" => $item->name,
                'leaveType' => $item->leave_type,
                'startDate' => $item->start_date,
                'endDate' => $item->end_date,
                'isSpecialLeave' => $item->is_special_leave,
                'specialLeaveType' => $item->special_leave_type,
                'specialLeaveComments' => $item->special_leave_comments,
                'isPaid' => $item->is_paid,
            ];
        });

        $travel_expense_approver_model = TravelExpenseApprover::where("approver_id", $user_id)->with("travelExpense", function ($query) {
            $query->where("status", "pending");
        })->get();

        $travel_expense_approver_collect = $travel_expense_approver_model->map(function ($item) {
            return [
                'id' => $item->id,
                //Define location from/to
                'departureCity' => $item->departure_city,
                "departureCountry" => $item->departureCountry?->name,
                'arrivalCity' => $item->arrival_city,
                "arrivalCountry" => $item->arrivalCountry?->name,
                //Define from date/time information
                'fromDate' => $item->from_date,
                'fromDepartureTime' => $item->from_departure_time,
                'fromArrivalTime' => $item->from_arrival_time,
                'fromDiscrepancy' => $item->from_discrepancy,
                //Define to date/time information
                'toDate' => $item->to_date,
                'toDepartureTime' => $item->to_departure_time,
                'toArrivalTime' => $item->to_arrival_time,
                'toDiscrepancy' => $item->to_discrepancy,

                //Additional information
                'requestType' => $item->request_type_id,
                'projectId' => $item->project_id,
                'sendForApproval' => $item->status != 'draft', // Set the value based on the status
            ];
        });

        return response()->json([
            "tasks" => $task_collect,
            "vacationApproveList" => $vacation_approver_collect,
            "travelExpenseApproveList" => $travel_expense_approver_collect,
        ]);
    }
}
