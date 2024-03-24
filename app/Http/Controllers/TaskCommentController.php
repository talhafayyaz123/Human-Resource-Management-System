<?php

namespace App\Http\Controllers;

use App\Models\MyTask;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskCommentController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'taskId' => 'required|exists:my_tasks,id',
        ]);
        $comments = TaskComment::where('task_id', $request->taskId)->orderBy('created_at', 'desc')->get()->map(function ($comment) {
            return [
                'id' => $comment->id,
                'createdAt' => Carbon::parse($comment->created_at)->diffForHumans(),
                'body' => $comment->body,
                'userId' => $comment->user_id,
                'taskId' => $comment->task_id,

            ];
        });

        return response()->json(['data' => $comments]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string',
            'userId' => 'required|string',
            'taskId' => 'required|exists:my_tasks,id',
        ]);

        $task_comment = new TaskComment();
        $task_comment->body = $request->input('body');
        $task_comment->user_id = $request->input('userId');
        $task_comment->task_id = $request->input('taskId');
        $task_comment->save();

        return response()->json(['message' => 'Task comment created successfully']);;
    }

    public function showLatest(Request $request)
    {
        $request->validate([
            'taskId' => 'required|exists:my_tasks,id',
        ]);
        $task = MyTask::findOrFail($request->taskId);
        $latest_task_comment = $task->taskComments()->latest()->first();
        if (!empty($latest_task_comment)) {
            return response()->json(['data' => [
                'id' => $latest_task_comment->id,
                'createdAt' => Carbon::parse($latest_task_comment?->created_at)->diffForHumans(),
                'body' => $latest_task_comment->body,
                'userId' => $latest_task_comment->user_id,
                'taskId' => $latest_task_comment->task_id,
            ]]);
        }
        return response()->json(['data' => '']);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string',
            'userId' => 'required|string',
            'taskId' => 'required|exists:my_tasks,id',
        ]);

        $task_comment = TaskComment::findOrFail($id);
        $task_comment->body = $request->input('body');
        $task_comment->user_id = $request->input('userId');
        $task_comment->task_id = $request->input('taskId');
        $task_comment->save();

        return response()->json(['message' => 'Task comment updated successfully']);
    }

    public function destroy($id)
    {
        $comment = TaskComment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Task comment deleted successfully']);
    }
}
