<?php

namespace App\Http\Controllers;

use App\Models\LeadStatus;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Request as staticRequest;

class LeadStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:lead-status.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:lead-status.create', ['only' => ['store']]);
        $this->middleware('check.permission:lead-status.edit', ['only' => ['update']]);
        $this->middleware('check.permission:lead-status.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use CustomHelper;

    public function index(Request $request): Response|JsonResponse
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new LeadStatus;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $leadStatus = $model->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($leadStatus) => [
                'id' => $leadStatus->id,
                'name' => $leadStatus->name,
                'createdAt' => Carbon::parse($leadStatus->created_at)->format('d/m/y'),
            ]);
        return response()->json(['operatingSystem' => $leadStatus]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        ]);

        //Create Lead Status
        $model = new LeadStatus;
        $model->name = $request->name;
        $model->save();
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Sales Lead Status'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leadStatus = LeadStatus::where("id", $id)->firstOrFail();
        return response()->json(['operatingSystem' => [
            'id' => $leadStatus->id,
            "name" => $leadStatus->name,
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

        $model = LeadStatus::where(["id" => $id])->first();
        $model->name = $request->name;
        $model->save();
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LeadStatus::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }
}
