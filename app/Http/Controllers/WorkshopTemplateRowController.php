<?php

namespace App\Http\Controllers;

use App\Utils\Logger;
use Illuminate\Http\Request;
use App\Http\Requests\WorkshopTemplateRowRequest;
use App\Models\WorkshopTemplateRow;
use App\Services\WorkshopTemplates\WorkshopTemplatesRowService;
use App\Http\Resources\WorkshopTemplateRowResource;
use App\Traits\CustomHelper;

class WorkshopTemplateRowController extends Controller
{

    use CustomHelper;

    public WorkshopTemplatesRowService $workshopTemplatesRowService;

    public function __construct(WorkshopTemplatesRowService $workshopTemplatesRowService)
    {
        $this->workshopTemplatesRowService = $workshopTemplatesRowService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkshopTemplateRowRequest $request)
    {

        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect($validated_data));

        $workshop_templates_row = $this->workshopTemplatesRowService->createWorkshopTemplateRow($data);

        return response()->json([
            "success" => true,
            "message" => "Record created successfully",
            "id" => $workshop_templates_row->id
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
        $workshop_templates_row = WorkshopTemplateRow::findOrFail($id);
        return new WorkshopTemplateRowResource($workshop_templates_row);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WorkshopTemplateRowRequest $request, $id)
    {
        $workshop_templates_row = WorkshopTemplateRow::findOrFail($id);

        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect($validated_data));

        $this->workshopTemplatesRowService->updateWorkshopTemplateRow($workshop_templates_row, $data);

        return response()->json([
            "success" => true,
            "message" => "Record updated successfully",
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
        $workshop_templates_row = WorkshopTemplateRow::findOrFail($id);
        $this->workshopTemplatesRowService->deleteWorkshopTemplateRow($workshop_templates_row);

        return response()->json([
            "success" => true,
            "message" => "Record deleted successfully"
        ]);
    }
}
