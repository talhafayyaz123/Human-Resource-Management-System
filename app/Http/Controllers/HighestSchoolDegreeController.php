<?php

namespace App\Http\Controllers;

use App\Models\HighestSchoolDegree;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;

class HighestSchoolDegreeController extends Controller
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
        $model = new HighestSchoolDegree;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $highest_school_degrees = $model->orderBy('created_at')->paginate($per_page)
            ->withQueryString()
            ->through(fn ($type) => [
                'id' => $type->id,
                'name' => $type->name
            ]);

        return response()->json([
            'data' => $highest_school_degrees
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

        $highest_school_degree = new HighestSchoolDegree();
        $highest_school_degree->name = $request->name;
        $highest_school_degree->save();

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
        $highest_school_degree = HighestSchoolDegree::findOrFail($id);

        return response()->json([
            "id" => $highest_school_degree->id,
            "name" => $highest_school_degree->name
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
        $highest_school_degree = HighestSchoolDegree::findOrFail($id);

        $request->validate([
            'name' => 'required|string'
        ]);

        $highest_school_degree->name = $request->name;
        $highest_school_degree->save();

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
        $highest_school_degree = HighestSchoolDegree::findOrFail($id);
        $highest_school_degree->delete();

        return response()->json([
            "success" => true,
            "message" => "Record Deleted Successfully"
        ]);
    }
}
