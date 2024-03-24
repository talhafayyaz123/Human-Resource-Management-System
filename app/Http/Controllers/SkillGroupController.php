<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Models\SkillGroup;
use App\Models\UserTeam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as staticRequest;

class SkillGroupController extends Controller
{

    use CustomHelper;

    public function __construct()
    {
        $this->middleware('check.permission:skill-group.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:skill-group.create', ['only' => ['store']]);
        $this->middleware('check.permission:skill-group.edit', ['only' => ['update']]);
        $this->middleware('check.permission:skill-group.delete', ['only' => ['destroy']]);
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
        $model = new SkillGroup();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->with('groupSkills')->orderBy('created_at')->filter(staticRequest::only('search'))->paginate($per_page)
            ->through(fn ($model) => [
                'id' => $model->id,
                'name' => $model->name,
                'skills' => $model->groupSkills->pluck('name'),
                'setOfSkills' => $model->groupSkills,
                'createdAt' => Carbon::parse($model->created_at)->format('d/m/y'),
            ]);
        return response()->json(['skillGroup' => $model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        return $this->extracted($request);
    }

    /**
     * Display user Job data
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $model = SkillGroup::with('groupSkills')->where('id', $id)->first();
        if ($model) {
            return  response()->json([
                'id' => $model->id,
                'name' => $model->name,
                'skills' => $model->groupSkills->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];
                }),
                'teams' => $model->skillGroupTeams->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];
                }),
                'jobs' => $model->skillGroupJobs->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'jobTitle' => $item->j_title,
                    ];
                }),
            ]);
        }
        return  response()->json([
            'id' => '',
            'name' => '',
            'skills' => '',
            'jobs' => '',
            'teams' => '',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
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
        $model = SkillGroup::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function extracted(Request $request, $id = null): JsonResponse
    {
        $request->validate([
            "name" => "required|string",
            "skills" => "required|array",
            "skills.*" => "integer|exists:App\Models\Skill,id",
        ]);

        //Create or update Skill Groups
        if ($id)
            $model = SkillGroup::findOrFail($id);
        else
            $model = new SkillGroup;

        $model->name = $request->name;
        
        $model->save();

        //Skills Add or update
        $skills = collect($request->skills);
        $model->groupSkills()->sync($skills);


        //Jobs Add or update
        if (!empty($request->jobs)) {
            $jobs = collect($request->jobs);

            // Get the existing job IDs associated with the skill group
            $existingJobs = $model->skillGroupJobs()->pluck('jobs.id')->toArray();
            // Filter out the jobs that are already associated
            $newJobs = $jobs->diff($existingJobs)->all();

            if (!empty($newJobs)) {
                $model->skillGroupJobs()->syncWithoutDetaching($newJobs);
            }
        }

        //Teams Add or update
        if (!empty($request->teams)) {
            $teams = collect($request->teams);

            // Get the existing user_teams IDs associated with the skill group
            $existingTeams = $model->skillGroupTeams()->pluck('user_teams.id')->toArray();
            // Filter out the user_teams that are already associated
            $newTeams = $teams->diff($existingTeams)->all();

            if (!empty($newTeams)) {
                $model->skillGroupTeams()->syncWithoutDetaching($newTeams);
            }
        }
        $content = [
            'moduleAction' => isset($id) ? 'updateSkillGroup' : 'createSkillUpdate',
            'data' => [
                'skillGroup' => $model?->toArray(),
            ],
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $model);


        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Skills Group'])], 200);
    }
}
