<?php

namespace App\Http\Controllers;

use App\Models\JobLevel;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Request as staticRequest;

class JobLevelController extends Controller
{

    public function __construct()
    {
        $this->middleware('check.permission:job-level.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:job-level.create', ['only' => ['store']]);
        $this->middleware('check.permission:job-level.edit', ['only' => ['update']]);
        $this->middleware('check.permission:job-level.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     use CustomHelper;
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new JobLevel;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        
        $jobLevel = $model->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($jobLevel) => [
                'id' => $jobLevel->id,
                'level_name' => $jobLevel->level_name,
                'experienceStart' => $jobLevel->experience_start,
                'experienceEnd' => $jobLevel->experience_end,
                'targetSalary' => $jobLevel->target_salary,
                'limitSalary' => $jobLevel->limit_salary,
                'createdAt' => Carbon::parse($jobLevel->created_at)->format('d/m/y'),
            ]);
        return response()->json(['jobLevel' => $jobLevel]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->extracted($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $jobLevel = JobLevel::where("id", $id)->firstOrFail();
        return response()->json(['jobLevel' => [
            'id' => $jobLevel->id,
            'levelName' => $jobLevel->level_name,
            'experienceStart' => $jobLevel->experience_start,
            'experienceEnd' => $jobLevel->experience_end,
            'targetSalary' => $jobLevel->target_salary,
            'limitSalary' => $jobLevel->limit_salary,
            'createdAt' => Carbon::parse($jobLevel->created_at)->format('d/m/y'),
        ]]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->extracted($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        JobLevel::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function extracted(Request $request, $id = null): JsonResponse
    {
        $request->validate([
            "levelName" => "required|string",
            "experienceStart" => "required|integer",
            "experienceEnd" => "required|integer|gte:experienceStart",
            "targetSalary" => "required",
            "limitSalary" => "required",
        ]);

        //Create JobLevel
        if ($id)
            $model = JobLevel::where(["id" => $id])->first();
        else
            $model = new JobLevel;

        $model->level_name = $request->levelName;
        $model->experience_start = $request->experienceStart;
        $model->experience_end = $request->experienceEnd;
        $model->target_salary = $request->targetSalary;
        $model->limit_salary = $request->limitSalary;
        $model->save();
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Skills Status'])], 200);
    }
}
