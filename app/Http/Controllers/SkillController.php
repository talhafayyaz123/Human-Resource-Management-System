<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Models\Skill;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as staticRequest;

class SkillController extends Controller
{
    use CustomHelper;

    public function __construct()
    {
        $this->middleware('check.permission:skills.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:skills.create', ['only' => ['store']]);
        $this->middleware('check.permission:skills.edit', ['only' => ['update']]);
        $this->middleware('check.permission:skills.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): Response|JsonResponse
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new Skill();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->orderBy('skills.created_at')->filter(staticRequest::only('search'))->paginate($per_page)
            ->through(fn ($model) => [
                'id' => $model->id,
                'name' => $model->name,
                'level' => $model->skillLevel?->name,
                'isRequired' => $model->is_required,
                'createdAt' => Carbon::parse($model->created_at)->format('d/m/y'),
            ]);
        return response()->json(['skills' => $model]);
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
            "name" => "required|string",
            "description" => "required|string",
            "level" => "required|integer",
        ]);

        //Create Skill
        $model = new Skill;
        $model->name = $request->name;
        $model->description = $request->description;
        $model->skill_level_id = $request->level;
        $model->is_required = isset($request->is_required) ? $request->is_required : 0;
        $model->save();
        $content = [
            'moduleAction' => 'storeSkill',
            'data' => $model?->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $model);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Skills Status'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $skills = Skill::where("id", $id)->firstOrFail();
        return response()->json(['skills' => [
            'id' => $skills->id,
            "name" => $skills->name,
            "description" => $skills->description,
            "level" => $skills->skill_level_id,
            "is_required" => $skills->is_required,
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
            "name" => "required|string",
            "description" => "required|string",
            "level" => "required|integer",
        ]);

        //Update Skill
        $model = Skill::where(["id" => $id])->first();
        $model->name = $request->name;
        $model->description = $request->description;
        $model->skill_level_id = $request->level;
        $model->is_required = isset($request->is_required) ? $request->is_required : 0;
        $model->save();
        $content = [
            'moduleAction' => 'updateSkill',
            'data' => $model?->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $model);
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        Skill::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }
}
