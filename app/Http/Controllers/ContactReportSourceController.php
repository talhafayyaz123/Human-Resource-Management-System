<?php

namespace App\Http\Controllers;

use App\Models\ContactReportSource;
use App\Utils\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Request as staticRequest;

class ContactReportSourceController extends Controller
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
        $model = new ContactReportSource;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $contact_report_sources = $model->filter(staticRequest::only('search'))->paginate($per_page);
        $model_collect = $contact_report_sources->map(function ($model) {
            return [
                'id' => $model->id,
                'name' => $model->name,
                'created_at' => $model->created_at
            ];
        });
        return response()->json([
            'data' => $model_collect,
            'links' => $contact_report_sources->links(),
            'current_page' => $contact_report_sources->currentPage(),
            'from' => $contact_report_sources->firstItem(),
            'last_page' => $contact_report_sources->lastPage(),
            'path' => request()->url(),
            'per_page' => $contact_report_sources->perPage(),
            'to' => $contact_report_sources->lastItem(),
            'total' => $contact_report_sources->total(),
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
        $request->validate(['name' => 'required|string']);
        $contact_report_source = new ContactReportSource;
        $contact_report_source->name = $request->name;
        $contact_report_source->save();
        return response()->json(['success' => true, trans('messages.record_saved_success', ['name' => 'Contact report source'])]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact_report_source = ContactReportSource::findOrFail($id);
        return response()->json([
            'data' => [
                'id' => $contact_report_source->id,
                'name' => $contact_report_source->name
            ],
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
        $request->validate(['name' => 'required|string']);
        $contact_report_source = ContactReportSource::findOrFail($id);
        $contact_report_source->name = $request->name;
        $contact_report_source->save();

        return response()->json(['success' => true, trans('messages.record_updated_success', ['name' => 'Contact report source'])]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact_report_source = ContactReportSource::findOrFail($id);
        $contact_report_source->delete();
        return response()->json(['success' => true, trans('messages.record_deleted_success', ['name' => 'Contact report source'])]);
    }
}
