<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Http\Resources\ModuleHistoryResource;
use App\Models\UserDepartment;
use App\Models\UserTeam;
use App\Traits\CustomHelper;
use App\Models\UserProfileData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;

class UserDepartmentController extends Controller
{
    use CustomHelper;
    /**
     * Run on instantiate
     */
    public function __construct()
    {
        // $this->middleware('check.permission:user-department.list', ['only' => ['show']]);
        $this->middleware('check.permission:user-department.create', ['only' => ['store']]);
        $this->middleware('check.permission:user-department.edit', ['only' => ['update']]);
        $this->middleware('check.permission:user-department.delete', ['only' => ['destroy']]);
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

        $model = new UserDepartment;
        $get_permissions = $request->get('auth_user_permissions');
        if (!in_array('user-department.list', $get_permissions)) {
            $user_id = $request->get('auth_user_id');
            $model = $model->whereHas('departmentHead', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->orWhereHas('teams.teamMembers', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->orWhereHas('teams.teamLead', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        }
        $models = $model->when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })->paginate($per_page);
        $paginate_data = $models->toArray();

        $model_collect = $models->map(function ($item) {
            return [
                'id' => $item->id,
                "name" => $item->name,
                "location" => !empty($item->location) ? ["id" => $item->location->id, "name" => $item->location->name] : '',
                "departmentHead" => !empty($item->departmentHead) ? $item->departmentHead->last_name . ', ' . $item->departmentHead->first_name : '-',
            ];
        });
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model_collect = $this->applySorting($model_collect, $sort_by, $sort_order);
        }
        return response()->json([
            'data' => $model_collect,
            'links' => $paginate_data['links'],
            'count' => $paginate_data['total'],
        ], 200);
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
        $model = new UserDepartment;
        $this->saveData($model, $request, 'createDepartment');

        return response()->json(['message' => 'Record created successfully.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = UserDepartment::find($id);
        return response()->json(["data" => [
            'id' => $model->id,
            "name" => $model->name,
            "locationId" => $model->location_id,
            "isTopLevel" => $model->is_top_level == 1,
            "departmentHeadId" => !empty($model->departmentHead) ? $model->departmentHead->id : '',
            "departmentTeams" => $model->teams->map(function ($item_team) {
                return [
                    "id" => $item_team->id,
                    "name" => $item_team->name
                ];
            }),
            "departments" => $model->is_top_level ? $model->childDepartments->map(function ($department) {
                return [
                    'id' => $department->id,
                    'name' => $department->name,
                ];
            }) : [],
            "employees" => $model?->employees?->map(function ($item) {
                return [
                    'id' => $item->id,
                    'employee' => $item->first_name . ' ' . $item->last_name,
                    'firstName' => $item->first_name,
                    'lastName' => $item->last_name,
                    'email' => $item->email,
                    'userId' => $item->user_id,
                    'dateOfBirth' => $item->date_of_birth,
                    'teams' => [],
                    'departments' => '',
                    "personalNumber" => '',
                    "jobTitle" => '',
                    "joinDate" => '',
                    "location" => '',
                    "exitDate" => '',
                    "grossSalary" => '',
                    "profilePicUrl" => null
                ];
            }) ?? [],
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
        $model = UserDepartment::find($id);
        $this->saveData($model, $request, 'updateDepartment');

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
        $model->department_head_id = $request->departmentHeadId ?? null;  //User id as "string" saved

        if (UserDepartment::where('is_top_level', true)->count() > 0 && $request->isTopLevel && !$model->is_top_level) {
            throw new Exception('Another department has already been set as top level. Only one department can be set as top level.');
        }

        // check if there is already a top level department, if no then add the employees and set the is_top_level to true
        // if not then set the user_department_id of all the employees related to this model to null and also set is_top_level to false
        $model->employees()->update(['user_department_id' => null]);

        $model->save();

        if ($request->isTopLevel) {
            $model->is_top_level = true;
            if (isset($request->departments)) {
                $department_children_connect_ids = collect($request->departments)->pluck('id');
                $model->childDepartments()->sync($department_children_connect_ids);
            }
            UserProfileData::whereIn('id', $request->employees)->update(['user_department_id' => $model->id]);
        } else {
            $model->is_top_level = false;
        }

        $model->save();

        //Assign teams to relevant department
        $model->teams()->update(['department_id' => null]);
        if (!empty($request->departmentTeams)) {
            UserTeam::whereIn('id', $request->departmentTeams)->update(['department_id' => $model->id]);
        }
        $model->teams = $model->teams;
        $content = [
            'moduleAction' => $action,
            'data' => $model->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $model);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = UserDepartment::find($id);
        $model->delete();
        $model->deleted_at = Carbon::now()->toIso8601ZuluString();
        $content = [
            'moduleAction' => 'deleteDepartment',
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
        $model = UserDepartment::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }
}
