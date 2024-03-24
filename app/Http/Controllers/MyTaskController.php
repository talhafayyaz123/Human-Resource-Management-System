<?php

namespace App\Http\Controllers;

use App\Enums\TaskType;
use App\Helpers\OrderHelper;
use App\Models\MyTask;
use App\Models\TaskAssignee;
use App\Models\TaskBoard;
use App\Models\TaskContentType;
use App\Models\UploadedFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MyTaskController extends Controller
{

    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $my_tasks = MyTask::paginate($per_page)
            ->withQueryString()
            ->through(function ($task) {
                return [
                    'id' => $task->id,
                    'icon' => $task->icon,
                    'headline' => $task->headline,
                    'priority' => $task->priority,
                    'color' => $task->color,
                    'dueDate' => Carbon::parse($task->due_date)->toDateString(),
                    'taskType' => $task->task_type,
                    'content' => $task->contentType->map(function ($content) {
                        return [
                            'id' => $content->id,
                            'type' => $content->type,
                            'value' => $content->value,
                            'isChecked' => $content->is_checked == 1
                        ];
                    }),
                    'userId' => $task->creator_id,
                    'assignees' => $task->assignees->map(function ($assignee) {
                        return $assignee->user_id;
                    }),
                    'status' => 'new'
                ];
            });

        return response()->json($my_tasks, 200);
    }

    public function show($id)
    {
        $my_task = MyTask::findOrFail($id);
        return response()->json([
            'id' => $my_task->id,
            'icon' => $my_task->icon,
            'headline' => $my_task->headline,
            'color' => $my_task->color,
            'priority' => $my_task->priority,
            'dueDate' => Carbon::parse($my_task->due_date)->toDateString(),
            'taskType' => $my_task->task_type,
            'content' => $my_task->contentType->map(function ($content) {
                return [
                    'id' => $content->id,
                    'type' => $content->type,
                    'value' => $content->value,
                    'isChecked' => $content->is_checked == 1
                ];
            }),
            'taskImages' => $my_task->files()->exists() ? $my_task->files->map(function ($file) {
                return [
                    'name' => $file->viewable_name, 'size' => $file->storage_size,
                    'base64' => base64_encode(Storage::disk('public')->get('public/assets/files/' . $file->storage_name))
                ];
            }) : [],
            'userId' => $my_task->creator_id,
            'assignees' => $my_task->assignees->map(function ($assignee) {
                return $assignee->user_id;
            }),
            'status' => 'new'
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon' => 'required|string',
            'color' => 'required|string',
            'headline' => 'required|string',
            'priority' => 'required|string',
            'statusId' => 'required',
            'dueDate' => 'required|date',
            'taskType' => ['required', Rule::in(TaskType::ALL)],
            'content' => 'nullable|array',
            'userId' => 'required|string',
            'content.*.type' => 'required|string',
            'content.*.value' => 'required|string',
            'content.*.isChecked' => 'nullable|boolean',
            'assignees' => 'required|array',
            'taskImages' => 'nullable|array',
            'taskImages.*.name' => 'required|string',
            'taskImages.*.base64' => 'required|string',
            'assignees.*.userId' => 'required|string',
            'boardAssignees' => 'nullable|array',
            'boardAssignees.*.userId' => 'required|string',
            'timeTrackerType' => 'nullable|in:company,government'
        ]);
        $task = new MyTask;
        $task = $this->saveData($task, $request);
        return response()->json(['message' => 'Task created successfully']);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'icon' => 'required|string',
            'color' => 'required|string',
            'headline' => 'required|string',
            'priority' => 'required|string',
            'dueDate' => 'required|date',
            'statusId' => 'required',
            'taskType' => ['required', Rule::in(TaskType::ALL)],
            'content' => 'nullable|array',
            'userId' => 'required|string',
            'content.*.type' => 'required|string',
            'content.*.value' => 'required|string',
            'taskImages' => 'nullable|array',
            'taskImages.*.name' => 'required|string',
            'taskImages.*.base64' => 'required|string',
            'assignees' => 'required|array',
            'assignees.*.userId' => 'required|string',
            'boardAssignees' => 'nullable|array',
            'boardAssignees.*.userId' => 'required|string',
            'timeTrackerType' => 'nullable|in:company,government'
        ]);
        $task = MyTask::findOrFail($id);
        $task = $this->saveData($task, $request, true);
        return response()->json(['message' => 'Task updated successfully']);
    }



    private function saveData(MyTask $task, Request $request, $is_edit = false): MyTask
    {
        $task->icon = $request->icon;
        $task->color = $request->color;
        $task->headline = $request->headline;
        $task->priority = $request->priority;
        $task->due_date = Carbon::parse($request->dueDate);
        $task->task_type = $request->taskType;
        $task->creator_id = $request->userId;
        $task->task_status_id = $request->statusId;
        $task->time_tracker_type = $request->timeTrackerType ?? null;
        if (!$is_edit) {
            $order = OrderHelper::order($request->upperOrder ?? null);
            $task->order = $order;
        }
        $task->save();

        if (isset($request->taskImages)) {
            $this->processImages($task, $request->taskImages);
        }

        if ($request->has('content')) {
            $this->saveContentTypes($task, $request->all());
        }
        if ($request->has('assignees')) {
            $this->saveAssignees($task, $request->all());
        }
        return $task;
    }

    private function saveContentTypes(MyTask $task, array $data): MyTask
    {
        if ($task->contentType) {
            $task->contentType()->delete();
        }
        $content_types = collect($data['content'])->map(function ($item) {
            return new TaskContentType([
                'type' => $item['type'],
                'value' => $item['value'],
                'is_checked' => $item['isChecked'] ?? ''
            ]);
        });
        $task->contentType()->saveMany($content_types);
        return $task;
    }

    private function saveAssignees(MyTask $task, array $data): MyTask
    {
        if ($task->assignees) {
            $task->assignees()->delete();
        }
        $assigned_users = collect($data['assignees']);
        $board_users = $assigned_users;
        $non_board_users = collect();
        if (!empty($data['boardAssignees'])) {
            $board_assignees = collect($data['boardAssignees']);
            $board_users = $assigned_users->intersectByKeys($board_assignees);
            $non_board_users = $assigned_users->diffUsing($board_assignees, function ($user1, $user2) {
                return strcmp($user1['userId'], $user2['userId']);
            });
        }
        if (!empty($board_users)) {
            $assignees = $board_users->map(function ($item) {
                return new TaskAssignee([
                    'user_id' => $item['userId'],
                ]);
            });
            $task->assignees()->saveMany($assignees);
        }
        if (!empty($non_board_users)) {
            $this->saveNonBoardUsers($task, $non_board_users);
        }
        return $task;
    }


    private function saveNonBoardUsers(MyTask $task, $non_board_users)
    {
        $new_task = $task->replicate(['task_status_id', 'creator_id']);
        $non_board_users = $non_board_users->map(function ($item) use (&$new_task) {
            $board = TaskBoard::where('board_admin_id', $item['userId'])->first();
            if (!empty($board)) {
                $task_status = $board->taskStatuses()->where('name', 'To do')->first();
                if (!empty($task_status)) {
                    $new_task->task_status_id = $task_status->id;
                    $new_task->creator_id = $item['userId'];
                    $new_task->save();
                    $assignees = [
                        new TaskAssignee(['user_id' => $item['userId']])
                    ];
                    $new_task->assignees()->saveMany($assignees);
                }
            }
        });
        return $task;
    }

    public function destroy($id)
    {
        $task = MyTask::findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Task has been deleted']);
    }

    public function updateStatus(Request $request)
    {
        $request->validate(['statusId' => 'required', 'taskId' => 'required']);
        $task = MyTask::findOrFail($request->taskId);
        $task->task_status_id = $request->statusId;
        $task->save();
        return response()->json(['message' => 'Task moved successfully'], 200);
    }

    public function returnTask(Request $request)
    {
        $request->validate(['taskId' => 'required']);
        $task = MyTask::findOrFail($request->taskId);
        $task->assignees()->delete();
        $task_assignee = new TaskAssignee([
            'user_id' => $task->creator_id,
        ]);
        $task->assignees()->save($task_assignee);
        return response()->json(['message' => 'Task returned successfully']);
    }

    public function handOver(Request $request)
    {
        $request->validate([
            'taskId' => 'required',
            'assignees' => 'required|array',
            'assignees.*.userId' => 'required|string'
        ]);
        $task = MyTask::findOrFail($request->taskId);
        $this->saveAssignees($task, $request->all());
        return response()->json(['message' => 'Task handovered successfully']);
    }

    public function assumeTask(Request $request)
    {
        $request->validate([
            'taskId' => 'required',
            'userId' => 'required',
        ]);
        $task = MyTask::findOrFail($request->taskId);
        $task->assignees()->delete();
        $task_assignee = new TaskAssignee([
            'user_id' => $request->userId,
        ]);
        $task->assignees()->save($task_assignee);
        return response()->json(['message' => 'Task assumed successfully']);
    }

    public function orderTask(Request $request)
    {
        $request->validate([
            'taskId' => 'required|exists:my_tasks,id',
            'upperOrder' => 'nullable',
            'lowerOrder' => 'nullable'
        ]);
        $my_task = MyTask::findOrFail($request->taskId);
        $order = OrderHelper::order(
            $request->upperOrder ?? null,
            $request->lowerOrder ?? null
        );
        $my_task->order = $order;
        $my_task->save();
        return response()->json(['message' => 'Task updated successfully']);
    }

    public function resubmit(Request $request)
    {
        $request->validate([
            'taskId' => 'required|exists:my_tasks,id',
            'resubmitDate' => 'required|date'
        ]);

        $my_task = MyTask::findOrFail($request->taskId);
        $my_task->resubmit_date = Carbon::parse($request->resubmitDate);
        $my_task->save();
        return response()->json(['message' => 'Task resubmitted successfully']);
    }

    public function processImages($model, $attachments): void
    {
        if ($model->files()->exists()) {
            $model->files()->delete();
        }
        foreach ($attachments as $attachment) {
            $original_name = $attachment['name'];
            $base64Decode = base64_decode($attachment['base64'], true);
            $file_name_to_store = $model->id . '__' . time() . '.' . $original_name;
            Storage::disk('public')->put('public/myTasks/files/' . $file_name_to_store, $base64Decode);
            $size = Storage::disk('public')->size('public/myTasks/files/' . $file_name_to_store);
            $uploaded_file = new UploadedFile();
            $uploaded_file->storage_name = $file_name_to_store;
            $uploaded_file->viewable_name = $original_name;
            $uploaded_file->storage_size = $size;
            $uploaded_file->fileable()->associate($model);
            $uploaded_file->save();
        }
    }

    public function imageUpload(Request $request)
    {
        $file = $request->file;
        $original_name = $file->getClientOriginalName();
        $file_name_to_store = time() . '_' . $original_name;
        Storage::disk('public')->putFileAs('public/myTasks/files/', $file, $file_name_to_store);
        return response()->json(["message" => "Image upload successfully", "file" => Storage::disk('public')->url('public/myTasks/files/' . $file_name_to_store)], 200);
    }
}
