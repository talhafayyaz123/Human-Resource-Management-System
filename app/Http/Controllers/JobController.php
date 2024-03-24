<?php

namespace App\Http\Controllers;

use App\Http\Resources\ModuleHistoryResource;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Traits\SetGlobalNumber;
use Illuminate\Support\Carbon;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Request as staticRequest;

class JobController extends Controller
{
    use SetGlobalNumber;
    use CustomHelper;
    
    public function __construct()
    {
        $this->middleware('check.permission:job.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:job.create', ['only' => ['store']]);
        $this->middleware('check.permission:job.edit', ['only' => ['update']]);
        $this->middleware('check.permission:job.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new Job();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $jobs = $model->orderBy('jobs.created_at')->filter(staticRequest::only('search'))->paginate($per_page)
            ->through(fn ($jobs) => [
                'id' => $jobs->id,
                'jobNumber' => $jobs->j_number,
                'jobTitle' => $jobs->j_title,
                'jobLevel' => $jobs->jobLevel->level_name,
                'contractType' => $jobs->contracType->name,
                'createdAt' => Carbon::parse($jobs->created_at)->format('d/m/y'),
            ]);
        return response()->json(['jobs' => $jobs]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->extracted($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $model = Job::with('jobSkillGroups', 'jobLevel', 'contracType')->where('id', $id)->first();
        if ($model) {
            return  response()->json([
                'jobs' => [
                    'id' => $model->id,
                    'jobNumber' => $model->j_number,
                    'jobTitle' => $model->j_title,
                    'jobLevel' => $model->jobLevel,
                    'contractType' => $model->contracType,
                    'skillGroup' => $model->jobSkillGroups,
                    'coreFunctions' => $model->core_functions,
                    'qualifications' => $model->qualifications,
                    'jobDescription' => $model->j_description,
                ]
            ]);
        }
        return  response()->json([
            'id' => "",
            'jobNumber' => "",
            'jobTitle' => "",
            'jobLevel' => "",
            'contractType' => "",
            'skillGroup' => "",
            'coreFunctions' => "",
            'qualifications' => "",
            'jobDescription' => "",
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return $this->extracted($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $model = Job::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function extracted(Request $request, $id = null): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "jobTitle" => "required|string",
            "jobDescription" => "required|string",
            "qualifications" => "required|string",
            "coreFunctions" => "required|string",
            "jobLevel" => "required|integer",
            "contractType" => "required|integer",
            "skillGroup" => "required|array",
            "skillGroup.*" => "integer|exists:App\Models\SkillGroup,id",
        ]);

        //Create Skill
        if ($id)
            $model = Job::findOrFail($id);
        else
            $model = new Job;

        $model->j_number = $this->globalNumber($model, 'jobs', 'J', '100000');
        $model->j_title = $request->jobTitle;
        $model->job_level_id = $request->jobLevel;
        $model->form_of_contract_id = $request->contractType;
        $model->core_functions = $request->coreFunctions;
        $model->qualifications = $request->qualifications;
        $model->j_description = $request->jobDescription;
        $model->save();

        //Skill Group Add or update
        $skillGroup = collect($request->skillGroup);
        $model->jobSkillGroups()->sync($skillGroup);

        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Jobs'])], 200);
    }
}
