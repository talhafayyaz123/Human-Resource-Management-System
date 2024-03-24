<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContinuousImprovementProcessResource;
use App\Http\Requests\ContinuousImprovementProcessRequest;
use App\Services\ContinuousImprovementProcessService;
use App\Models\ContinuousImprovementProcess;
use App\Models\UserProfileData;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CIPApprovalResource;
use App\Http\Requests\CIPApprovalRequest;

class ContinuousImprovementProcessController extends Controller
{
    use CustomHelper;
    public ContinuousImprovementProcessService $continuousImprovementProcessService;

    public function __construct(ContinuousImprovementProcessService $continuousImprovementProcessService)
    {
        $this->middleware('check.permission:continuous-improvement-process.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:continuous-improvement-process.create', ['only' => ['store']]);
        $this->middleware('check.permission:continuous-improvement-process.edit', ['only' => ['update']]);
        $this->middleware('check.permission:continuous-improvement-process.delete', ['only' => ['destroy']]);
        $this->continuousImprovementProcessService = $continuousImprovementProcessService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new ContinuousImprovementProcess();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        // Paginate the FleetCar records
        $continuous_improvement_processes = $model->paginate($per_page);
        // Return the paginated FleetCarResource
        return ContinuousImprovementProcessResource::collection($continuous_improvement_processes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContinuousImprovementProcessRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        try {
            DB::transaction(function () use ($data) {
                $this->continuousImprovementProcessService->createContinuousImprovementProcess($data);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Continuous Improvement Process'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find the FleetCar record by ID
        $continuous_improvement_processes = ContinuousImprovementProcess::findOrFail($id);

        // Return a single FleetCarResource for the found record
        return new ContinuousImprovementProcessResource($continuous_improvement_processes);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContinuousImprovementProcessRequest $request, $id)
    {
        $validated_data = $request->validated();
        $continuous_improvement_processes = ContinuousImprovementProcess::findOrFail($id);
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $continuous_improvement_processes = $this->continuousImprovementProcessService->updateContinuousImprovementProcess($continuous_improvement_processes, $data);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Continuous Improvement Process'])], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $continuous_improvement_processes = ContinuousImprovementProcess::findOrFail($id);
        $this->continuousImprovementProcessService->deleteContinuousImprovementProcess($continuous_improvement_processes);
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Continuous Improvement Process'])], 200);
    }

    /**
     * get the approval listing
     * @param Request $request
     * @return JSONResponse
     */
    public function getApprovalListing(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $user = UserProfileData::where('user_id', $this->getAuthUserId($request))->first();
        $models = $user->continuousImprovementProcesses()->paginate($per_page)->withQueryString();
        return response()->json([
            'data' => CIPApprovalResource::collection($models),
            'links' => $models->links(),
            'current_page' => $models->currentPage(),
            'from' => $models->firstItem(),
            'last_page' => $models->lastPage(),
            'path' => request()->url(),
            'per_page' => $models->perPage(),
            'to' => $models->lastItem(),
            'total' => $models->total(),
        ]);
    }

    /**
     * sets the approval status of a CIP Request
     * @param CIPApprovalRequest $request
     * @return JSONResponse
     */
    public function setApprovalStatus(CIPApprovalRequest $request)
    {
        $validated_data = $request->validated();
        try {
            DB::transaction(function () use ($validated_data) {
                $this->continuousImprovementProcessService->setApprovalStatus($validated_data);
            });
            return response()->json([
                'success' => true,
                'message' => 'Status has been updated succcessfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
