<?php

namespace App\Http\Controllers;

use App\Helpers\OrderHelper;
use App\Models\TaskBoard;
use App\Models\TaskStatus;
use App\Models\TaskStatusTransition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskStatusController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'taskBoardId' => 'required',
        ]);
        $task_board = TaskBoard::findOrFail($request->taskBoardId);
        $search_term = $request->input('search') ?? '';
        $status_filter = $request->input('statusId') ?? '';
        $task_statuses = $task_board->taskStatuses()->when($status_filter, function ($query) use ($status_filter) {
            $query->where('id', $status_filter);
        })->orderBy('order')->get();
        $status_data = $task_statuses?->map(function ($data) use ($request, $task_board, $search_term) {
            $tasks = $data->myTasks()->when($search_term, function ($subQuery) use ($search_term) {
                $subQuery->where('headline', 'like', "%$search_term%");
            })->when($request->resubmit, function ($subQuery) {
                $subQuery->whereNotNull('resubmit_date');
            }, function ($subQuery) {
                $subQuery->where(function ($query) {
                    $query->whereNull('resubmit_date')
                        ->orWhereDate('resubmit_date', '<=', now());
                });
            })->orderBy('order')->get();
            if (!empty($request->assignees)) {
                $tasks = $tasks->filter(function ($task) use ($request) {
                    $matching_assignees = array_intersect($request->assignees, $task->assignees->pluck('user_id')->toArray());
                    return count($matching_assignees) > 0;
                })->values();
            }
            return [
                'id' => $data->id,
                'name' => $data->name,
                'color' => $data?->color,
                'order' => $data?->order,
                'isDoneStatus' => $data?->is_done_status == 1,
                'tasks' => $tasks?->map(function ($task) use ($task_board) {
                    $is_exclamation = false;
                    $due_date = Carbon::parse($task->due_date)->timezone('Europe/Berlin');
                    $current_date_time = Carbon::now()->timezone('Europe/Berlin');
                    $time_difference_in_hours = $current_date_time->diffInHours($due_date);
                    $escalation_hours = $task_board->is_default ? 24 : $task_board->escalation_hours;
                    if (isset($escalation_hours) && $escalation_hours > 0) {
                        if ($time_difference_in_hours < $task_board->escalation_hours) {
                            $is_exclamation = true;
                        } else {
                            $is_exclamation = false;
                        }
                    }
                    return [
                        'id' => $task->id,
                        'icon' => $task->icon,
                        'color' => $task->color,
                        'headline' => $task->headline,
                        'statusId' => $task->task_status_id,
                        'isExclamation' => $is_exclamation,
                        'priority' => $task->priority,
                        'order' => $task->order,
                        'timeTrackerType' => $task->time_tracker_type ?? '',
                        'dueDate' => $task->due_date,
                        'taskType' => $task->task_type,
                        'resubmitDate' => isset($task->resubmit_date) ? Carbon::parse($task->resubmit_date)->toDateString() : '',
                        'content' => $task->contentType?->map(function ($type) {
                            return ['id' => $type->id, 'type' => $type->type, 'value' => $type->value, 'isChecked' => $type->is_checked == 1];
                        }),
                        'taskImages' => $task->files()->exists() ? $task->files->map(function ($file) {
                            $base64 =  base64_encode(Storage::disk('public')->get('public/myTasks/files/' . $file->storage_name));
                            $mime = Storage::disk('public')->mimeType('public/myTasks/files/' . $file->storage_name);
                            return [
                                'name' => $file->viewable_name, 'size' => $file->storage_size,
                                'base64' => "data:$mime;base64,$base64",
                            ];
                        }) : [],
                        'userId' => $task->creator_id,
                        'assignees' => $task->assignees?->map(function ($assignee) {

                            return ['id' => $assignee->id, 'userId' => $assignee->user_id];
                        })
                    ];
                })
            ];
        });
        return response()->json(['data' => $status_data, 'assignees' => $status_data->flatMap(function ($status) {
            return $status['tasks'];
        })->flatMap(function ($task) {
            return $task['assignees'];
        })->pluck('userId')->unique()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'taskBoardId' => 'required|exists:task_boards,id',
            'isDoneStatus' => 'nullable|boolean'
        ]);
        $task_status = new TaskStatus;
        $this->saveData($task_status, $request);
        return response()->json(['message' => 'Task status created successfully']);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'taskBoardId' => 'required|exists:task_boards,id'
        ]);
        $task_status = TaskStatus::findOrFail($id);
        $this->saveData($task_status, $request, true);
        return response()->json(['message' => 'Task status updated successfully']);
    }

    private function saveData(TaskStatus $task_status, $request, $is_edit = false): TaskStatus
    {
        $task_status->name = $request->name;
        $task_status->task_board_id = $request->taskBoardId;

        $task_status->color = $request->color ?? '';
        if (!$is_edit) {
            $order = OrderHelper::order($request->upperOrder ?? null);
            $task_status->order = $order;
        }
        $task_status->save();
        if ($request->isDoneStatus) {
            TaskStatus::where('task_board_id', $request->taskBoardId)
                ->where('id', '!=', $task_status->id)
                ->update(['is_done_status' => false]);
            $task_status->is_done_status = true;
            $task_status->save();
        }
        return $task_status;
    }

    public function destroy($id)
    {
        $task_status = TaskStatus::findOrFail($id);
        $task_status->delete();
        return response()->json(['message' => 'Task board deleted successfully']);
    }


    public function taskTransitions(Request $request)
    {
        $request->validate([
            'fromStatusId' => 'required|exists:task_statuses,id',
            'toStatusId' => 'required|exists:task_statuses,id'
        ]);
        $from_status_id = $request->fromStatusId;
        $to_task_id = $request->toStatusId;
        $existing_transition = TaskStatusTransition::where('from_status_id', $from_status_id)
            ->where('to_status_id', $to_task_id)
            ->first();
        if ($existing_transition) {
            $existing_transition->from_status_id = $from_status_id;
            $existing_transition->to_status_id = $to_task_id;
            $existing_transition->save();
            return response()->json(['message' => 'Status transition updated successfully'], 200);
        } else {
            $task_transition = new TaskStatusTransition();
            $task_transition->to_status_id = $request->toStatusId;
            $task_transition->from_status_id = $request->fromStatusId;
            $task_transition->save();
            return response()->json(['message' => 'Task transitioned successfully']);
        }
    }

    public function removeTaskTransitions(Request $request)
    {
        $request->validate([
            'fromStatusId' => 'required|exists:task_statuses,id',
            'toStatusId' => 'required|exists:task_statuses,id'
        ]);
        $from_status_id = $request->fromStatusId;
        $to_task_id = $request->toStatusId;
        $transition = TaskStatusTransition::where('from_status_id', $from_status_id)
            ->where('to_status_id', $to_task_id)
            ->first();

        if ($transition) {
            $transition->delete();
            return response()->json(['message' => 'Task transition removed successfully']);
        } else {
            return response()->json(['error' => 'Task transition not found'], 404);
        }
    }

    public function listTaskTranitions($id)
    {
        $board = TaskBoard::findOrFail($id);
        $statuses = $board->taskStatuses;
        $task_transition_data = $statuses?->map(function ($status) {
            return [
                'id' => $status->id,
                'name' => $status->name,
                'outgoingTransitions' => $status?->outgoingTransitions?->map(function ($data) {
                    return [
                        'id' => $data->id,
                        'fromStatus' => $data->from_status_id,
                        'toStatus' => $data->to_status_id,
                    ];
                }),
                'incomingTransitions' => $status?->incomingTransitions?->map(function ($data) {
                    return [
                        'id' => $data->id,
                        'fromStatus' => $data->from_status_id,
                        'toStatus' => $data->to_status_id,
                    ];
                })
            ];
        });

        return response()->json(['data' => $task_transition_data]);
    }

    public function orderStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:task_statuses,id',
            'upperOrder' => 'nullable',
            'lowerOrder' => 'nullable'
        ]);
        $task_status = TaskStatus::findOrFail($request->id);
        $order = OrderHelper::order(
            $request->upperOrder ?? null,
            $request->lowerOrder ?? null
        );
        $task_status->order = $order;
        $task_status->save();
        return response()->json(['message' => 'Status updated successfully']);
    }
}
