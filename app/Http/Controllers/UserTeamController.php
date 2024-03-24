<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Models\UserTeam;
use App\Models\UserDepartment;
use App\Traits\CustomHelper;
use App\Utils\Logger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserTeamController extends Controller
{
    use CustomHelper;
    /**
     * Run on instantiate
     */
    public function __construct()
    {
        //  $this->middleware('check.permission:user-team.list', ['only' => ['show']]);
        $this->middleware('check.permission:user-team.create', ['only' => ['store']]);
        $this->middleware('check.permission:user-team.edit', ['only' => ['update']]);
        $this->middleware('check.permission:user-team.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage;

        $model = new UserTeam;

        $get_permissions = $request->get('auth_user_permissions');
        if (!in_array('user-team.list', $get_permissions)) {
            $user_id = $request->get('auth_user_id');
            $model = $model->whereHas('teamLead', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->orWhereHas('teamMembers', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        }
        $models = $model->when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })->paginate($per_page);

        $model_collect = $models->map(function ($item) {
            return [
                'id' => $item->id,
                "name" => $item->name,
                "department" => !empty($item->department) ? ["id" => $item->department->id, "name" => $item->department->name] : '',
                "teamLead" => $item->teamLead,
                'teamMembers' => $item->teamMembers->map(function ($member) {
                    return [
                        'memberId' => $member?->id,
                        'employee' => $member?->first_name . ' ' . $member?->last_name,
                        'skills' => $this->memberSkills($member?->jobData?->job?->jobSkillGroups)
                    ];
                }),
            ];
        });
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model_collect = $this->applySorting($model_collect, $sort_by, $sort_order);
        }
        return response()->json([
            'data' => $model_collect,
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
        ], 200);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
        ]);

        //Create Terms Of Payment
        $model = new UserTeam;
        $this->saveData($model, $request, 'createTeam');

        return response()->json(['message' => 'Record created successfully.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $model = UserTeam::find($id);
        return response()->json(["data" => [
            'id' => $model->id,
            "name" => $model->name,
            "departmentId" => !empty($model->department) ? $model->department->id : '',
            "teamLead" => !empty($model->teamLead) ? $model->teamLead->id : '', //Dropdown search on frontend based on id
            "teamMembers" => $model->teamMembers->map(function ($item_team) {
                return [
                    "id" => $item_team->id,
                    "firstName" => $item_team->first_name,
                    "lastName" => $item_team->last_name,
                ];
            }),
            'skillGroup' => $model->teamSkillGroups
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
        $request->validate([
            "name" => "required",
        ]);

        //Create Terms Of Payment
        $model = UserTeam::find($id);
        $this->saveData($model, $request, 'updateTeam');

        return response()->json(['message' => 'Record updated successfully.'], 200);
    }


    /**
     * Saves the data based on provided resource item
     *
     * @param   object  $model
     * @param   object  $request
     * @param   array   Response
     */
    public function saveData($model, $request, $action = null)
    {
        $model->name = $request->name;

        $model->team_lead_id = $request->teamLeadId; //User id as "string" saved
        $model->department_id = $request->departmentId;
        $model->save();

        $model->teamMembers()->sync($request->teamMembers);
        $model->team_members = $model->teamMembers;
        $content = [
            'moduleAction' => $action,
            'data' => $model->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $model);

        if ($request->skillGroup) {
            $skillGroup = collect($request->skillGroup);
            $model->teamSkillGroups()->sync($skillGroup);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = UserTeam::find($id);
        $model->delete();
        $model->team_members = $model->teamMembers;
        $model->deleted_at = Carbon::now()->toIso8601ZuluString();
        $content = [
            'moduleAction' => "deleteTeam",
            'data' => $model->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    /**
     * Restore the previously deleted source.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $model = UserTeam::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }

    /**
     * Fetch teams by department
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getTeamsByDepartment($id)
    {
        $teams = UserTeam::where("department_id", $id)->get();
        return response()->json(['data' => $teams->map(function ($team) {
            return [
                "id" => $team->id,
                "name" => $team->name,
                "teamLead" => [
                    "id" => $team->teamLead?->id ?? "-",
                    "name" => $team->teamLead?->first_name . " " . $team->teamLead?->last_name,
                    "userId" => $team->teamLead?->user_id,
                ]
            ];
        })], 200);
    }
}
