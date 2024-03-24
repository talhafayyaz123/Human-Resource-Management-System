<?php

namespace App\Http\Controllers;

use App\Models\TaskBoard;
use App\Models\TaskBoardUser;
use App\Models\TaskStatus;
use App\Helpers\OrderHelper;
use App\Models\TaskAssignee;
use App\Models\TaskStatusTransition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskBoardController extends Controller
{


    public function index(Request $request)
    {
        $request->validate([
            'userId' => 'required|string'
        ]);
        $task_board_users = TaskBoardUser::where('user_id', $request->userId)->get();
        $task_boards = $task_board_users->map(function ($board) {
            return [
                'id' => $board->taskBoard?->id,
                'name' => $board->taskBoard?->name,
                'menuColor' => $board->taskBoard?->menu_color,
                'headColor' => $board->taskBoard?->head_color,
                'isAdmin' => $board->user_id == $board->taskBoard?->user_id ? true : false,
                'boardAdminId' => $board->taskBoard?->board_admin_id,
                'isDefault' => $board->taskBoard?->is_default,
                'escalationHours' => $board->taskBoard?->escalation_hours,
                'escalationManager' => $board->taskBoard?->escalation_manager,
                'isEscalationModeNotify' => $board->taskBoard?->is_escalation_mode_notify,
                'isTaskCreationNotify' => $board->taskBoard?->is_task_creation_notify,
                'isTaskAssignedNotify' => $board->taskBoard?->is_task_assigned_notify,
                'shareableUsers' => $board->taskBoard?->taskBoardUsers?->map(fn ($user) => $user->user_id) ?? [],
                'standardIcon' => $board->taskBoard?->standard_icon,
                'standardColor' => $board->taskBoard?->standard_color,
                'standardPriority' => $board->taskBoard?->standard_priority,
                'standardTimeTracker' => $board->taskBoard?->standard_time_tracker,
            ];
        });
        return response()->json([
            'data' => $task_boards->filter(function ($board) {
                return isset($board['id']);
            }),
        ], 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'userId' => 'required|string',
            'menuColor' => 'nullable|string',
            'headColor' => 'nullable|string',
            'shareableUsers' => 'nullable|array',
            'shareableUsers.*.userId' => 'required|string',
            'useDefaultStatuses' => 'required|boolean',
            'statusTransitions' => 'nullable|array',
            'statuses' => 'required|array',
            'statuses.*.name' => 'required|string',
            'statuses.*.color' => 'required|string',
            'statuses.*.id' => 'required|uuid',
            'standardIcon' => 'nullable|string|max:255',
            'standardColor' => 'nullable|string|max:255',
            'standardPriority' => 'nullable|string|max:255',
            'standardTimeTracker' => 'nullable|string|max:255',

        ]);
        DB::transaction(function () use ($request) {
            $board = new TaskBoard();
            $board = $this->saveData($board, $request);
            $status_array = [];
            if ($request->useDefaultStatuses) {
                $status_array = $request->statuses;
            } else {
                $status_array = $request->statuses;
            }

            $statuses_id_mapped = $this->createStatuses($board, $status_array);
            if (!empty($request->statusTransitions)) {
                $this->createStatusTransitions($request->statusTransitions, $statuses_id_mapped);
            }
        });

        return response()->json(['message' => 'Task board created successfully']);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'userId' => 'required|string',
            'menuColor' => 'nullable|string',
            'headColor' => 'nullable|string',
            'shareableUsers' => 'nullable|array',
            'shareableUsers.*.userId' => 'required|string',
            'useDefaultStatuses' => 'required|boolean',
            'isEscalationModeNotify' => 'nullable|boolean',
            'isTaskCreationNotify' => 'nullable|boolean',
            'isTaskAssignedNotify' => 'nullable|boolean',
            'escalationHours' => 'nullable|between:0,99.99',
            'escalationManager' => 'nullable|string',
            'standardIcon' => 'nullable|string|max:255',
            'standardColor' => 'nullable|string|max:255',
            'standardPriority' => 'nullable|string|max:255',
            'standardTimeTracker' => 'nullable|string|max:255',
        ]);
        $board = TaskBoard::findOrFail($id);
        $board = $this->saveData($board, $request, true);
        return response()->json(['message' => 'Task board updated successfully']);
    }


    private function saveData(TaskBoard $board, $request, $is_edit = false): TaskBoard
    {
        $board->name = $request->name;
        if (!$is_edit)
            $board->is_default = false;
        $board->board_admin_id = $request->userId;
        $board->menu_color = $request->menuColor ?? '';
        $board->head_color = $request->headColor ?? '';
        $board->is_escalation_mode_notify = $request->isEscalationModeNotify ?? false;
        $board->is_task_creation_notify = $request->isTaskCreationNotify ?? false;
        $board->is_task_assigned_notify = $request->isTaskAssignedNotify ?? false;
        $board->escalation_hours = $request->escalationHours ?? null;
        $board->escalation_manager = $request->escalationManager ?? null;
        $board->standard_icon = $request->standardIcon ?? null;
        $board->standard_color = $request->standardColor ?? null;
        $board->standard_priority = $request->standardPriority ?? null;
        $board->standard_time_tracker = $request->standardTimeTracker ?? null;

        $board->save();
        if (!$is_edit && $request->has('shareableUsers')) {
            $board = $this->saveShareableUsers($board, $request->all());
        }
        return $board;
    }

    private function saveShareableUsers(TaskBoard $board, array $data): TaskBoard
    {
        $task_board_users = collect($data['shareableUsers'])->map(function ($item) {
            return new TaskBoardUser([
                'user_id' => $item['userId'],
            ]);
        });
        $board->taskBoardUsers()->saveMany($task_board_users);
        return $board;
    }

    public function destroy($id)
    {
        $task_board = TaskBoard::findOrFail($id);
        $task_board->delete();
        return response()->json(['message' => 'Task board deleted successfully']);
    }

    private function createStatuses(TaskBoard $task_board, array $statuses)
    {
        $status_order = null;
        $created_statuses_collection = [];
        collect($statuses)->map(function ($status_data) use ($task_board, &$status_order, &$created_statuses_collection) {
            $task_status = new TaskStatus;
            $task_status->name = $status_data['name'];
            $task_status->color = $status_data['color'];
            $task_status->is_done_status = $status_data['isDoneStatus'];
            $task_status->task_board_id = $task_board->id;
            $order = OrderHelper::order($status_order);
            $task_status->order = $order;
            $status_order = $order;
            $task_status->save();
            $created_statuses_collection[] = ['uuid' => $status_data['id'], 'id' => $task_status->id];
        });

        return $created_statuses_collection;
    }

    public function editShareableUsers($id, Request $request)
    {
        $request->validate([
            'shareableUsers' => 'nullable|array',
            'shareableUsers.*.userId' => 'required|string',
        ]);
        //need optimization - to do
        $task_board = TaskBoard::findOrFail($id);
        $board_admin = $task_board->board_admin_id;
        $shareable_users = $task_board->taskBoardUsers()->whereNot('user_id', $board_admin);
        $shareable_users->delete();
        //
        $this->saveShareableUsers($task_board, $request->all());
        return response()->json(['message' => 'Shared users edited successfully']);
    }

    private function createStatusTransitions($incoming_status_transitions, $mapped_statuses)
    {
        $collect_mapped_statuses = collect($mapped_statuses);
        $collect_incoming_transitions = collect($incoming_status_transitions);
        $collect_incoming_transitions?->map(function ($incoming_transition) use ($collect_mapped_statuses) {

            if (isset($incoming_transition['fromStatusId']) && $incoming_transition['toStatusId']) {

                $from_status_id = $collect_mapped_statuses->firstWhere('uuid', $incoming_transition['fromStatusId']);
                $to_status_id = $collect_mapped_statuses->firstWhere('uuid', $incoming_transition['toStatusId']);
                if (isset($from_status_id['id']) && isset($to_status_id['id'])) {
                    $task_transition = new TaskStatusTransition();
                    $task_transition->to_status_id = $to_status_id['id'];
                    $task_transition->from_status_id = $from_status_id['id'];
                    $task_transition->save();
                }
            }
        });
        return $collect_incoming_transitions;
    }

    public function getTaskBoard(Request $request)
    {
        $request->validate([
            'userId' => 'required|string'
        ]);


        $my_tasks_assignees = TaskAssignee::where('user_id', $request->userId)->get();
        $users_tasks = $my_tasks_assignees->map(function ($item) {
            if (!$item->task?->taskStatus?->is_done_status)
                return [
                    'id' => $item->task?->id,
                    'headline' => $item->task?->headline,
                    'icon' => $item->task?->icon,
                    'status' => $item->task?->taskStatus?->name,
                    'board' => $item->task?->taskStatus?->taskBoard,
                ];
        });

        $task_board_users = TaskBoardUser::where('user_id', $request->userId)->get();
        $task_boards = $task_board_users->map(function ($board) {
            return [
                'id' => $board->taskBoard?->id,
                'name' => $board->taskBoard?->name,
            ];
        });

        return response()->json([
            'boards' => $task_boards->filter(function ($board) {
                return isset($board['id']);
            }),
            'tasks' => $users_tasks->filter(function ($task) {
                return isset($task['id']);
            })->groupBy('status')->toArray()
        ], 200);
    }
}
