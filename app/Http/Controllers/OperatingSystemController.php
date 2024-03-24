<?php

namespace App\Http\Controllers;

use App\Models\OperatingSystem;
use App\Utils\Logger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;
use Illuminate\Http\{Response, JsonResponse};
use App\Traits\CustomHelper;

class OperatingSystemController extends Controller
{

    public function __construct()
    {
        $this->middleware('check.permission:operating-system.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:operating-system.create', ['only' => ['store']]);
        $this->middleware('check.permission:operating-system.edit', ['only' => ['update']]);
        $this->middleware('check.permission:operating-system.delete', ['only' => ['destroy']]);
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
        $model = new OperatingSystem;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $operatingSystem = $model->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($operatingSystem) => [
                'id' => $operatingSystem->id,
                'name' => $operatingSystem->name,
                'createdAt' => Carbon::parse($operatingSystem->created_at)->format('d/m/y'),
            ]);
        return response()->json(['operatingSystem' => $operatingSystem]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response|JsonResponse
    {
        $request->validate([
            "name" => "required|string",
        ]);

        //Create Operating System
        $model = new OperatingSystem;
        $model->name = $request->name;
        $model->save();
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Operating System'])], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): Response|JsonResponse
    {
        $operatingSystem = OperatingSystem::where("id", $id)->firstOrFail();
        return response()->json(['operatingSystem' => [
            'id' => $operatingSystem->id,
            "name" => $operatingSystem->name,
        ]]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): Response|JsonResponse
    {
        $request->validate([
            "name" => "required",
        ]);

        $model = OperatingSystem::where(["id" => $id])->first();
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
    public function destroy($id): Response|JsonResponse
    {
        OperatingSystem::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }
}