<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;


class PlaningBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __construct()
    {
        $this->middleware('check.permission:planning-dashboard.list', ['only' => ['index']]);
    }
    public function index(Request $request)
    {
        $projects = Project::where('status', 'new');
        if ($request->has('search')) {
            $search = $request->input('search');
            $projects->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('company', function ($companyQuery) use ($search) {
                        $companyQuery->where('company_name', 'like', '%' . $search . '%');
                    });
            });
        }
        // Get the filtered projects
        $projects = $projects->get();

        $project_collection = $projects->map(function ($item) {
            return [
                'id' => $item->id,
                'resourceId' => $item->id,
                'projectNumber' => $item->project_number,
                'name' => $item->name,
                'status' => $item->status,
                "plannedStartDate" => $item->planned_start_date,
                "actualStartDate" => $item->actual_start_date,
                "startDate" => $item->actual_start_date ?? "",
                "earliestStartDate" => $item->earliest_start_date,
                "actualFinishedDate" => $item->actual_finished_date,
                "endDate" => $item->actual_finished_date ?? "",
                "expectedFinishedDate" => $item->expected_finished_date,
                "plannedFinishedDate" => $item->planned_finished_date,
                "estimatedTime" => $item->estimated_time ?? 0,
                "neededTime" => $item->needed_time,
                "companyId" => $item->company_id,
                'companyName' => $item?->company?->company_name,
                "userId" => $item->user_id,
                "description" => $item->description,
                "isDragged" => (bool)$item->is_dragged,
                "milestoneCount" => $item->milestones->count(),
            ];
        });
        return response()->json([
            'data' => $project_collection,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        //Create Project
        $model = Project::where("id", $request->id)->first();
        $model->actual_start_date = $request->actualStartDate;
        $model->actual_finished_date = $request->actualFinishedDate;
        $model->is_dragged = true;
        $model->save();

        return response()->json(['message' => 'Record updated successfully.'], 200);
    }
}