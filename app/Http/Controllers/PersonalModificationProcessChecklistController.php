<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalModificationProcessChecklist;
use App\Traits\CustomHelper;

class PersonalModificationProcessChecklistController extends Controller
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
        $model = new PersonalModificationProcessChecklist;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $models = $model->paginate($per_page)->withQueryString()->through(fn ($item) => [
            'id' => $item->id,
            'process' => $item->process,
            'text' => $item->text
        ]);
        return $models;
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
            'text' => 'required|string',
            'process' => 'required|in:name-change,bank-account-change,change-of-health-insurance-company,change-of-address,change-of-tax-class,change-of-child-allowance'
        ]);

        $checklist = new PersonalModificationProcessChecklist();
        $checklist->text = $request->text;
        $checklist->process = $request->process;
        $checklist->save();

        return response()->json([
            "success" => true,
            "message" => "Record created successfully"
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
        $checklist = PersonalModificationProcessChecklist::findOrFail($id);

        return response()->json([
            'id' => $checklist->id,
            'process' => $checklist->process,
            'text' => $checklist->text
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
        $request->validate([
            'text' => 'required|string',
            'process' => 'required|in:name-change,bank-account-change,change-of-health-insurance-company,change-of-address,change-of-tax-class,change-of-child-allowance'
        ]);

        $checklist = PersonalModificationProcessChecklist::findOrFail($id);
        $checklist->text = $request->text;
        $checklist->process = $request->process;
        $checklist->save();

        return response()->json([
            "success" => true,
            "message" => "Record updated successfully"
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
        $checklist = PersonalModificationProcessChecklist::findOrFail($id);
        $checklist->delete();

        return response()->json([
            "success" => true,
            "message" => "Record deleted successfully"
        ]);
    }
}
