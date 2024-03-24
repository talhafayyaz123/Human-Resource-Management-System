<?php

namespace App\Http\Controllers;

use App\Http\Resources\TimeCheckingResource;
use App\Models\TimeTrackerCompany;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;

class TimeCheckingController extends Controller
{
    use CustomHelper;

    public function __construct()
    {
        $this->middleware('check.permission:time-checking.list', ['only' => ['index']]);
        $this->middleware('check.permission:time-checking.update', ['only' => ['store']]);
    }

    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 200;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new TimeTrackerCompany;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->with(['company', 'user'])
            ->whereNotIn('time_tracker_companies.status', ['pr', 'billed']);

        if ($request->get('userId')) {
            $model->where('user_id', $request->get('userId'));
        }

        if ($request->get('companyId')) {
            $model->where('company_id', $request->get('companyId'));
        }

        if (isset($request->status) && in_array($request->status, [0, 1])) {
            $model->where('time_checking_status', (int)$request->get('status'));
        }

        if (isset($request->status) && $request->status == 2) {
            $model->whereNull('time_checking_status');
        }

        if ($request->get('type')) {
            $type = $this->getType($request->get('type'));
            $model->where('module_type', $type);
        }

        $timeTrackerCompany = $model->get();
        $totalTime = $timeTrackerCompany->sum('time');
        $kulnazTime = $timeTrackerCompany->where('is_goodwill', 1)->sum('time');


        $models = $model->paginate($per_page);

        return response()->json([
            'data' => TimeCheckingResource::collection($models),
            'totalTime' => $totalTime,
            'kulanzTime' => $kulnazTime,
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
        ]);
    }

    private function getType($type)
    {
        $moduleTypes = [
            'task' => 'App\\Models\\Task',
            'newEntry' => 'newEntry',
            'ticket' => 'App\\Models\\TicketComment',
            'ams' => 'App\\Models\\ApplicationManagementService',
        ];
        return $moduleTypes[$type];
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);
        $model = TimeTrackerCompany::find($id);
        $model->time_checking_status = $request->status;
        $model->save();
        return response()->json([
            'message' => 'Record successfully updated.',
        ], 200);
    }
}
