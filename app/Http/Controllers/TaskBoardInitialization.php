<?php

namespace App\Http\Controllers;

use App\Models\TaskBoard;
use App\Models\TaskStatus;
use App\Models\TaskBoardUser;
use App\Helpers\OrderHelper;
use Illuminate\Http\Request;

class TaskBoardInitialization extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'userId' => 'required|string'
        ]);
        $task_board = TaskBoard::where('board_admin_id', $request->userId)->where('is_default', 1)->first();
        if (empty($task_board)) {
            $task_board = new TaskBoard();
            $task_board->name = 'My board';
            $task_board->is_default = true;
            $task_board->board_admin_id = $request->userId;
            $task_board->save();
            $this->createStatuses($task_board, [['name' => 'To do', 'color' => 'black'], ['name' => 'In progress', 'color' => 'blue'], ['name' => 'Done', 'color' => 'green', 'isDoneStatus' => true], ['name' => 'Blocked', 'color' => 'red']]);
            // add user to taskBoardUsers
            $task_board->taskBoardUsers()->save(new TaskBoardUser([
                'user_id' => $request->userId,
            ]));
        }
        return response()->json([
            "success" => true
        ], 200);
    }


    private function createStatuses(TaskBoard $task_board, array $statuses)
    {
        $status_order = null;
        collect($statuses)->map(function ($status_data) use ($task_board, &$status_order) {
            $task_status = new TaskStatus;
            $task_status->name = $status_data['name'];
            $task_status->color = $status_data['color'];
            $order = OrderHelper::order($status_order);
            $task_status->order = $order;
            $status_order = $order;
            $task_status->is_done_status = $statuses['isDoneStatus'] ?? false;
            $task_status->task_board_id = $task_board->id;
            $task_status->save();
        });

        return $task_board;
    }
}
