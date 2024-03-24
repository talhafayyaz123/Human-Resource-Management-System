<?php

namespace App\Http\Controllers;

use App\Models\AssetEmployeeListText;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class AssetEmployeeTextController extends Controller
{
    use CustomHelper;
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
        $employee_texts = new AssetEmployeeListText();
        if ($sort_by && $sort_order) {
            $employee_texts = $this->applySortingBeforePagination($employee_texts, $sort_by, $sort_order);
        }
        $employee_texts = $employee_texts->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($employee_text) => [
                'id' => $employee_text->id,
                'assetEmployeeText' => $employee_text->asset_employee_text,
                'createdAt' => Carbon::parse($employee_text->created_at)->format('d/m/y'),
            ]);
        return response()->json(['data' => $employee_texts]);
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
            "assetEmployeeText" => "required|string",
        ]);
        $employee_text = new AssetEmployeeListText();
        $employee_text->asset_employee_text = $request->assetEmployeeText;
        $employee_text->save();
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Asset employee text'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee_text = AssetEmployeeListText::where("id", $id)->first();
        return response()->json(['employeeText' => [
            'id' => $employee_text->id,
            'assetEmployeeText' => $employee_text->asset_employee_text,
            'createdAt' => Carbon::parse($employee_text->created_at)->format('d/m/y'),
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
            "assetEmployeeText" => "required|string",
        ]);
        $employee_text = AssetEmployeeListText::findOrFail($id);
        $employee_text->asset_employee_text = $request->assetEmployeeText;
        $employee_text->save();
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Asset employee text'])], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee_text = AssetEmployeeListText::findOrFail($id);
        $employee_text->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }
}
