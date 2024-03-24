<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModifyMyDataRequest;
use App\Http\Resources\ModifyMyDataManagerResource;
use App\Http\Resources\ModifyMyDataResource;
use App\Models\ModifyMyData;
use App\Models\ModifyMyDataManager;
use App\Models\PersonalModificationProcessChecklist;
use App\Models\Task;
use App\Models\UserProfileData;
use App\Services\ModifyMyData\ModifyMyDataService;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ModifyMyDataController extends Controller
{
    use CustomHelper;

    protected ModifyMyDataService $modifyMyDataService;

    public function __construct(ModifyMyDataService $modifyMyDataService)
    {
        $this->modifyMyDataService = $modifyMyDataService;
    }

    public function index(Request $request)
    {
        $userId = $this->getAuthUserId($request);
        $per_page = $request->perPage ?? 25;

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new ModifyMyData();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        $model = $model->where('user_id', $userId);

        // Paginate the ProjectProtocol records
        $myRequests = $model->paginate($per_page);
        return response()->json([
            'data' => ModifyMyDataResource::collection($myRequests),
            'links' => $myRequests->links(),
            'meta' => [
                'current_page' => $myRequests->currentPage(),
                'from' => $myRequests->firstItem(),
                'last_page' => $myRequests->lastPage(),
                'path' => request()->url(),
                'per_page' => $myRequests->perPage(),
                'to' => $myRequests->lastItem(),
                'total' => $myRequests->total(),
            ]
        ]);
    }

    public function store(ModifyMyDataRequest $request)
    {
        $validated_data = $request->validated();
        $validated_data['userId'] = $this->getAuthUserId($request);
        $this->modifyMyDataService->create($validated_data);
        return response()->json([
            "success" => true,
            "message" => "Record created successfully.",
        ]);
    }

    public function show($id)
    {
        $model = ModifyMyData::find($id);
        return response()->json(['data' => new ModifyMyDataResource($model)]);
    }

    public function update(ModifyMyDataRequest $request, $id)
    {
        $model = ModifyMyData::find($id);
        if ($model && $model->status == 'open') {
            $validated_data = $request->validated();
            $this->modifyMyDataService->update($model, $validated_data);
            return response()->json([
                "success" => true,
                "message" => "Record updated successfully.",
            ]);
        }
        return response()->json(['message' => "Request is not in open status"], 400);
    }

    public function destroy($id)
    {
        $model = ModifyMyData::find($id);
        if ($model && $model->status == 'open') {
            $model->delete();
            return response()->json([
                "success" => true,
                "message" => "Record deleted successfully.",
            ]);
        }
        return response()->json(['message' => "Request is not in open status"], 400);
    }

    public function requestForManager(Request $request)
    {
        $userId = $this->getAuthUserId($request);
        $userProfileData = UserProfileData::where('user_id', $userId)->first();
        if (!$userProfileData) {
            throw new \Exception('No user profile attached with this user');
        }
        $per_page = $request->perPage ?? 25;

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new ModifyMyDataManager();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        $requests = $model->where('manager_id', $userProfileData->id)->paginate($per_page);
        return response()->json([
            'data' => ModifyMyDataManagerResource::collection($requests),
            'links' => $requests->links(),
            'meta' => [
                'current_page' => $requests->currentPage(),
                'from' => $requests->firstItem(),
                'last_page' => $requests->lastPage(),
                'path' => request()->url(),
                'per_page' => $requests->perPage(),
                'to' => $requests->lastItem(),
                'total' => $requests->total(),
            ]
        ]);
    }

    public function showRequest($id)
    {
        $model = ModifyMyDataManager::find($id);
        if ($model && $model->modifyMyData) {
            $checkLists = PersonalModificationProcessChecklist::where('process', $model->modifyMyData->process)->get();
            $data = [
                'id' => $model->id,
                'process' => $model->modifyMyData?->process,
                'reason' => $model->modifyMyData?->reason,
                'changeRequestData' => $model->modifyMyData?->change_request_data,
                'requester' => $model->modifyMyData?->userProfileData ? [
                    'userId' => $model->modifyMyData?->userProfileData?->user_id,
                    'employee' => $model->modifyMyData?->userProfileData?->full_name,
                ] : null,
                'editor' => $model->modifyMyData?->changedManager?->employee ? [
                    'userId' => $model->modifyMyData?->changedManager?->employee?->user_id,
                    'employee' => $model->modifyMyData?->changedManager?->employee?->full_name,
                ] : null,
                'status' => $model->modifyMyData?->status,
                'createdAt' => $model->modifyMyData?->created_at->format('Y-m-d H:i:s'),
                'checkList' => $checkLists->map(function ($checkList) {
                    return [
                        'id' => $checkList->id,
                        'process' => $checkList->process,
                        'text' => $checkList->text
                    ];
                })
            ];
            return response()->json($data);
        }
        return response()->json(['message' => 'Invalid id.']);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:modify_my_data_managers,id',
            'status' => 'required|in:open,in-progress,released',
        ]);

        $model = ModifyMyDataManager::find($request->id);
        $modifyData = $model->modifyMyData;
        if ($modifyData->status != 'open') {
            if (!$model->changed_by) {
                throw new \Exception('This change process request is already been picked up');
            }
        }
        $modifyData->status = $request->status;
        $modifyData->save();
        if ($modifyData->status != 'open') {
            $model->changed_by = 1;
            $model->changed_at = Carbon::now();
            $model->save();
            $taskStatues = [
                'in-progress' => 'in-work',
                'released' => 'done',
            ];
            $employee = $model->employee;
            $taskDescription = $modifyData->taskable->where('employee_id', $employee->user_id)->first()->description;
            $newTaskDescription = $employee->full_name . " pickup this task. " . $taskDescription;
            $taskStatus = $taskStatues[$modifyData->status];
            $taskIds = $modifyData->taskable->where('employee_id', '<>', $employee->user_id)->pluck('id')->toArray();
            Task::whereIn('id', $taskIds)->update([
                'description' => $newTaskDescription,
                'status' => $taskStatus
            ]);
        }
        return response()->json(['message' => 'Status changed successfully']);
    }
}
