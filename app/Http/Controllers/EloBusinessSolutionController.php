<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use App\Models\EloBusinessSolutions;
use App\Services\EloBusinessSolutionService;
use Illuminate\Support\Facades\Request as staticRequest;
use App\Http\Resources\EloBusinessSolutionResource;
use App\Http\Requests\EloBusinessSolutionRequest;

class EloBusinessSolutionController extends Controller
{


    use CustomHelper;
    public EloBusinessSolutionService $eloBusinessSolutionService;

    public function __construct(EloBusinessSolutionService $eloBusinessSolutionService)
    {
        $this->middleware('check.permission:infrastructure-settings.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:infrastructure-settings.create', ['only' => ['store']]);
        $this->middleware('check.permission:infrastructure-settings.edit', ['only' => ['update']]);
        $this->middleware('check.permission:infrastructure-settings.delete', ['only' => ['destroy']]);
        $this->eloBusinessSolutionService = $eloBusinessSolutionService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /* $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new EloBusinessSolutions();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        // Paginate the FleetCar records
        $elo_business_solution = $model->filter(staticRequest::only('search', 'type', 'status'))
            ->paginate($per_page);
        // Return the paginated FleetCarResource
        return response()->json([
            'data' => EloBusinessSolutionResource::collection($elo_business_solution),
            'links' => $elo_business_solution->links(),
            'current_page' => $elo_business_solution->currentPage(),
            'from' => $elo_business_solution->firstItem(),
            'last_page' => $elo_business_solution->lastPage(),
            'path' => request()->url(),
            'per_page' => $elo_business_solution->perPage(),
            'to' => $elo_business_solution->lastItem(),
            'total' => $elo_business_solution->total(),
        ]); */
        $per_page = $request->perPage ?? 25;

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new EloBusinessSolutions;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $solution = $model->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($solution) => [
                'id' => $solution->id,
                'name' => $solution->name,
                'installName' => $solution->install_name,
                'type' => $solution->type
            ]);
        return response()->json(['eloBusinessSolutions' => $solution]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EloBusinessSolutionRequest $request)
    {
        $validated_data = $request->validated();
        $this->eloBusinessSolutionService->createEloBusinessSolution($validated_data);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Elo Business Solution'])], 200);
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
        $elo_business_solution = EloBusinessSolutions::findOrFail($id);

        // Return a single FleetCarResource for the found record
        return new EloBusinessSolutionResource($elo_business_solution);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EloBusinessSolutionRequest $request, $id)
    {
        $validated_data = $request->validated();
        $elo_business_solution = EloBusinessSolutions::findOrFail($id);
        $this->eloBusinessSolutionService->updateEloBusinessSolution($elo_business_solution,  $validated_data);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Elo Business Solution'])], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $elo_business_solution = EloBusinessSolutions::findOrFail($id);
        $this->eloBusinessSolutionService->deleteEloBusinessSolution($elo_business_solution);
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Elo Business Solution'])], 200);
    }

    public function eloBusinessSolutionsList(){
        $elo_business_solution = EloBusinessSolutions::get();
        return EloBusinessSolutionResource::collection($elo_business_solution);
    }
}
