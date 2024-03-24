<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlaLevelRequest;
use App\Http\Resources\SlaLevelResource;
use App\Models\SlaLevel;
use App\Services\SlaLevelService;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;


class SlaLevelController extends Controller
{
    use CustomHelper;
    public SlaLevelService $slaLevelService;

    public function __construct(SlaLevelService $slaLevelService)
    {
        $this->middleware('check.permission:sla-levels.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:sla-levels.create', ['only' => ['store']]);
        $this->middleware('check.permission:sla-levels.edit', ['only' => ['update']]);
        $this->middleware('check.permission:sla-levels.delete', ['only' => ['destroy']]);
        $this->slaLevelService = $slaLevelService;
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
        $model = new SlaLevel();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        // Paginate the FleetCar records
        $sla_levels = $model->paginate($per_page);
        // Return the paginated FleetCarResource
        return response()->json([
            'data' => SlaLevelResource::collection($sla_levels),
            'links' => $sla_levels->links(),
            'current_page' => $sla_levels->currentPage(),
            'from' => $sla_levels->firstItem(),
            'last_page' => $sla_levels->lastPage(),
            'path' => request()->url(),
            'per_page' => $sla_levels->perPage(),
            'to' => $sla_levels->lastItem(),
            'total' => $sla_levels->total(),
        ]);
    }
    /**
     * Stores the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(SlaLevelRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $this->slaLevelService->createSlaLevel($data);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'SLA Level'])], 200);
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
        $sla_level = SlaLevel::findOrFail($id);

        // Return a single FleetCarResource for the found record
        return new SlaLevelResource($sla_level);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(SlaLevelRequest $request, $id)
    {
        $validated_data = $request->validated();
        $sla_level = SlaLevel::findOrFail($id);
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $this->slaLevelService->updateSlaLevel($sla_level,  $data);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'SLA Level'])], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sla_level = SlaLevel::findOrFail($id);
        $this->slaLevelService->deleteSlaLevel($sla_level);
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'SLA Level'])], 200);
    }
}
