<?php

namespace App\Http\Controllers;

use App\Models\HighestEducationLevel;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;


class HighestEducationLevelController extends Controller
{
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
        $model = new HighestEducationLevel;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $highest_education_levels = $model->orderBy('created_at')->paginate($per_page)
            ->withQueryString()
            ->through(fn ($type) => [
                'id' => $type->id,
                'name' => $type->name
            ]);

        return response()->json([
            'data' => $highest_education_levels
        ]);
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
            'name' => 'required|string'
        ]);

        $highest_education_level = new HighestEducationLevel();
        $highest_education_level->name = $request->name;
        $highest_education_level->save();

        return response()->json([
            "success" => true,
            "message" => "Record Created Successfully"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $highest_education_level = HighestEducationLevel::findOrFail($id);

        return response()->json([
            "id" => $highest_education_level->id,
            "name" => $highest_education_level->name
        ]);
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
        $highest_education_level = HighestEducationLevel::findOrFail($id);

        $request->validate([
            'name' => 'required|string'
        ]);

        $highest_education_level->name = $request->name;
        $highest_education_level->save();

        return response()->json([
            "success" => true,
            "message" => "Record Updated Successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $highest_education_level = HighestEducationLevel::findOrFail($id);
        $highest_education_level->delete();

        return response()->json([
            "success" => true,
            "message" => "Record Deleted Successfully"
        ]);
    }
}
