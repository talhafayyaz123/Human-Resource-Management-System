<?php

namespace App\Http\Controllers;

use App\Models\AffectedGroup;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;

class AffectedGroupsController extends Controller
{
    
    use CustomHelper;
    public function __construct()
    {
        $this->middleware('check.permission:affected-groups.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:affected-groups.create', ['only' => ['store']]);
        $this->middleware('check.permission:affected-groups.edit', ['only' => ['update']]);
        $this->middleware('check.permission:affected-groups.delete', ['only' => ['destroy']]);
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
        $model = new AffectedGroup;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $affected_groups = $model->orderBy('created_at')
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($group) => [
                'id' => $group->id,
                'name' => $group->name,
            ]);
        return response()->json([
            'data' => $affected_groups
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

        $affected_group = new AffectedGroup();
        $affected_group->name = $request->name;
        $affected_group->save();

        return response()->json([
            'success' => true,
            'message' => 'Record created successfully'
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
        $affected_group = AffectedGroup::findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $affected_group->id,
                'name' => $affected_group->name
            ]
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
        $affected_group = AffectedGroup::findOrFail($id);

        $request->validate([
            'name' => 'required|string'
        ]);

        $affected_group->name = $request->name;
        $affected_group->save();

        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully'
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
        $affected_group = AffectedGroup::findOrFail($id);
        $affected_group->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully'
        ]);
    }
}
