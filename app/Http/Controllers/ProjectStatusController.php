<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectStatus;
use App\Traits\CustomHelper;


class ProjectStatusController extends Controller
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
        $model = new ProjectStatus;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $project_statuses = $model->orderBy('created_at')->paginate($per_page)
        ->withQueryString()
        ->through(fn ($status) => [
            "id" => $status->id,
            "name" => $status->name
        ]);

        return response()->json([
            'data' => $project_statuses
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

        $project_status = new ProjectStatus();
        $project_status->name = $request->name;
        $project_status->save();

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
        $project_status = ProjectStatus::findOrFail($id);

        return response()->json([
            "id" => $project_status->id,
            "name" => $project_status->name
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
        $project_status = ProjectStatus::findOrFail($id);

        $request->validate([
            'name' => 'required|string'
        ]);

        $project_status->name = $request->name;
        $project_status->save();

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
        $project_status = ProjectStatus::findOrFail($id);
        $project_status->delete();

        return response()->json([
            "success" => true,
            "message" => "Record Deleted Successfully"
        ]);
    }
}
