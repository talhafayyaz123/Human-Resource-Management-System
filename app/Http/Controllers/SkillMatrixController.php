<?php

namespace App\Http\Controllers;

use App\Models\SkillMatrix;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Request as staticRequest;

class SkillMatrixController extends Controller
{

    use CustomHelper;

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
        $model = new SkillMatrix();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->orderBy('created_at')->filter(staticRequest::only('search'))->paginate($per_page)
            ->through(fn ($model) => [
                'id' => $model->id,
                'name' => $model->name,
                'createdAt' => Carbon::parse($model->created_at)->format('d/m/y'),
            ]);
        return response()->json(['skillsMatrix' => $model]);
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
        $model = SkillMatrix::with('matrixSkillGroups', 'matrixSkillGroups.groupSkills',  'matrixTeams', 'matrixTeams.teamMembers.jobData.job.jobSkillGroups.groupSkills', 'matrixSkills', 'matrixTeamMembers')->where('id', $id)->first();
        if ($model) {
            return  response()->json([
                'id' => $model->id,
                'name' => $model->name,
                'skillGroup' => $model->matrixSkillGroups,
                'skills' => $model->matrixSkills,
                'teams' => $model->matrixTeams->map(function ($team) {
                    return [
                        'id' => $team->id,
                        'name' => $team->name,
                        'teamMembers' => $team->teamMembers->map(function ($member) {
                            return [
                                'memberId' => $member?->id,
                                'employee' => $member?->first_name . ' ' . $member?->last_name,
                                'skills' => $this->memberSkills($member?->jobData?->job?->jobSkillGroups)
                            ];
                        })
                    ];
                }),
                'teamMembers' => $model->matrixTeamMembers->map(function ($member) {
                    return [
                        'id' => $member?->id,
                        'employee' => $member?->first_name . ' ' . $member?->last_name,
                        'skills' => $this->memberSkills($member?->jobData?->job?->jobSkillGroups)
                    ];
                })
            ]);
        }
        return  response()->json([
            'id' => '',
            'name' => '',
            'skillGroup' => [],
            'skills' => [],
            'teamMembers' => [],
            'teams' => [],
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
        $model = SkillMatrix::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }

    /**
     * Display a listing of the matrix.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function matrix($id)
    {
        $model = SkillMatrix::with('matrixSkillGroups', 'matrixSkillGroups.groupSkills', 'matrixTeams', 'matrixTeams.teamMembers.jobData.job.jobSkillGroups.groupSkills', 'matrixSkills', 'matrixTeamMembers')->where('id', $id)->first();
        if ($model) {
            return response()->json([
                'data' => [
                    'id' => $model->id,
                    'name' => $model->name,
                    'skillGroup' => $model->matrixSkillGroups,
                    'skills' => $model->matrixSkills,
                    'teams' => $model->matrixTeams->map(function ($team) {
                        return [
                            'id' => $team->id,
                            'name' => $team->name,
                            'teamMembers' => $team->teamMembers->map(function ($member) {
                                return [
                                    'memberId' => $member?->id,
                                    'employee' => $member?->first_name . ' ' . $member?->last_name,
                                    'skills' => $this->memberSkills($member?->jobData?->job?->jobSkillGroups)
                                ];
                            })
                        ];
                    }),
                    'teamMembers' => $model->matrixTeamMembers->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'employee' => $item->first_name . ' ' . $item->last_name,
                            'firstName' => $item->first_name,
                            'lastName' => $item->last_name,
                            "jobTitle" => $item->jobData->job_title ?? '',
                            'skills' => $this->memberSkills($item?->jobData?->job?->jobSkillGroups),
                        ];
                    }),
                ]
            ]);
        }
    }
    private function memberSkills($groups)
    {
        $skills = [];
        if (!empty($groups)) {
            foreach ($groups as $group) {
                $skills[] = $group->groupSkills->map(function ($skill) {
                    return [
                        'skillId' => $skill?->id,
                        'skillName' => $skill?->name,
                        'skillLevel' => $skill?->level,
                    ];
                });
            }
        }
        return $skills;
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function extracted(Request $request, $id = null): JsonResponse
    {
        $request->validate([
            "name" => "required|string",
            "skillGroup" => "required|array",
            "teams" => "required|array",
            "skillGroup.*" => "integer|exists:App\Models\SkillGroup,id",
            "teams.*" => "exists:App\Models\UserTeam,id",

        ]);

        //Create or update Skill Groups
        if ($id)
            $model = SkillMatrix::findOrFail($id);
        else
            $model = new SkillMatrix;

        $model->name = $request->name;

        $model->save();

        //MatrixSkillGroups Add or update
        $skillGroup = collect($request->skillGroup);
        $model->matrixSkillGroups()->sync($skillGroup);

        //MatrixSkill Add or update
        if ($request->skills)
            $model->matrixSkills()->sync($request->skills);

        //MatrixTeams Add or update
        $teams = collect($request->teams);
        $model->matrixTeams()->sync($teams);

        //MatrixTeamsMembers Add or update
        if ($request->teamMembers)
            $model->matrixTeamMembers()->sync($request->teamMembers);

        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Skills Matrix'])], 200);
    }
}
