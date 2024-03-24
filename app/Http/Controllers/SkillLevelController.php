<?php

namespace App\Http\Controllers;

use App\Models\SkillLevel;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class SkillLevelController extends Controller
{
    use CustomHelper;

    public function __construct()
    {
        $this->middleware('check.permission:skill-level.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:skill-level.create', ['only' => ['store']]);
        $this->middleware('check.permission:skill-level.edit', ['only' => ['update']]);
        $this->middleware('check.permission:skill-level.delete', ['only' => ['destroy']]);
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
        $model = new SkillLevel();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->filter(staticRequest::only('search'))->paginate($per_page)
            ->through(fn($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'number' => $item->number,
            ]);
        return response()->json(['skillLevel' => $model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "number" => "required|integer",
        ]);

        //Create Skill
        SkillLevel::create([
            'name' => $request->name,
            'number' => $request->number,
        ]);
        return response()->json(['message' => 'Record saved successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $skillLevel = SkillLevel::find($id);
        if ($skillLevel) {
            return response()->json(['skillLevel' => [
                'id' => $skillLevel->id,
                "name" => $skillLevel->name,
                "number" => $skillLevel->number,
            ]]);
        }
        return response()->json(['message' => 'Invalid id provide'], 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required|string",
            "number" => "required|integer",
        ]);

        //Update Skill
        $model = SkillLevel::find($id);
        if ($model) {
            $model->update(['name' => $request->name, 'number' => $request->number]);
            return response()->json(['message' => 'Record updated successfully.'], 200);
        }
        return response()->json(['message' => 'Invalid id provide'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $model = SkillLevel::find($id);
        if ($model) {
            $model->delete();
            return response()->json(['message' => 'Record deleted successfully.'], 200);
        }
        return response()->json(['message' => 'Invalid id provide'], 400);
    }
}