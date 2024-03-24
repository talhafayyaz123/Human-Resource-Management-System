<?php

namespace App\Http\Controllers;

use App\Utils\Logger;
use Illuminate\Http\Request;
use App\Http\Requests\WorkshopTemplateColumnRequest;
use App\Models\WorkshopTemplateColumn;
use App\Services\WorkshopTemplates\WorkshopTemplatesColumnService;
use App\Http\Resources\WorkshopTemplateColumnResource;
use App\Traits\CustomHelper;

class WorkshopTemplateColumnController extends Controller
{

    use CustomHelper;

    public WorkshopTemplatesColumnService $workshopTemplatesColumnService;

    public function __construct(WorkshopTemplatesColumnService $workshopTemplatesColumnService)
    {
        $this->workshopTemplatesColumnService = $workshopTemplatesColumnService;
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
    public function store(WorkshopTemplateColumnRequest $request)
    {

        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect($validated_data));

        $workshop_templates_column = $this->workshopTemplatesColumnService->createWorkshopTemplateColumn($data);

        return response()->json([
            "success" => true,
            "message" => "Record created successfully",
            "id" => $workshop_templates_column->id
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
        $workshop_templates_column = WorkshopTemplateColumn::findOrFail($id);
        return new WorkshopTemplateColumnResource($workshop_templates_column);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WorkshopTemplateColumnRequest $request, $id)
    {
        $workshop_templates_column = WorkshopTemplateColumn::findOrFail($id);

        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect($validated_data));

        $this->workshopTemplatesColumnService->updateWorkshopTemplateColumn($workshop_templates_column, $data);

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
        $workshop_templates_column = WorkshopTemplateColumn::findOrFail($id);
        $this->workshopTemplatesColumnService->deleteWorkshopTemplateColumn($workshop_templates_column);

        return response()->json([
            "success" => true,
            "message" => "Record deleted successfully"
        ]);
    }
}
