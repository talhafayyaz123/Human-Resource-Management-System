<?php

namespace App\Http\Controllers;

use App\Models\GlobalNotificationList;
use Illuminate\Http\Request;

class GlobalNotificationListController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:global-notification.list', ['only' => ['index']]);
        $this->middleware('check.permission:global-notification.create', ['only' => ['store']]);
    }

    public function index()
    {
        $list = GlobalNotificationList::all();
        return response()->json([
            'userIds' => $list->pluck('user_id')->toArray()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'userIds' => 'required|array',
            'userIds.*' => 'required|string',
        ]);
        GlobalNotificationList::where('id', '<>', 0)->delete();
        foreach ($request->userIds as $userId) {
            GlobalNotificationList::create([
                'user_id' => $userId
            ]);
        }
        return response()->json([
            'message' => 'User added successfully.',
        ]);
    }
}
