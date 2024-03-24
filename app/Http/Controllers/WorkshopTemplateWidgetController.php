<?php

namespace App\Http\Controllers;

use App\Utils\Logger;
use Illuminate\Http\Request;
use App\Http\Requests\WorkshopTemplateWidgetRequest;
use App\Models\WorkshopTemplateWidget;
use App\Services\WorkshopTemplates\WorkshopTemplatesWidgetService;
use App\Http\Resources\WorkshopTemplateWidgetResource;
use App\Traits\CustomHelper;

class WorkshopTemplateWidgetController extends Controller
{

    use CustomHelper;

    public WorkshopTemplatesWidgetService $workshopTemplatesWidgetService;

    public function __construct(WorkshopTemplatesWidgetService $workshopTemplatesWidgetService)
    {
        $this->workshopTemplatesWidgetService = $workshopTemplatesWidgetService;
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
    public function store(WorkshopTemplateWidgetRequest $request)
    {

        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect($validated_data));

        $workshop_templates_widget = $this->workshopTemplatesWidgetService->createWorkshopTemplateWidget($data);

        return response()->json([
            "success" => true,
            "message" => "Record created successfully",
            "id" => $workshop_templates_widget->id
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
        $workshop_templates_widget = WorkshopTemplateWidget::findOrFail($id);
        return new WorkshopTemplateWidgetResource($workshop_templates_widget);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WorkshopTemplateWidgetRequest $request, $id)
    {
        $workshop_templates_widget = WorkshopTemplateWidget::findOrFail($id);

        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect($validated_data));

        $this->workshopTemplatesWidgetService->updateWorkshopTemplateWidget($workshop_templates_widget, $data);

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
        $workshop_templates_widget = WorkshopTemplateWidget::findOrFail($id);
        $this->workshopTemplatesWidgetService->deleteWorkshopTemplateWidget($workshop_templates_widget);

        return response()->json([
            "success" => true,
            "message" => "Record deleted successfully"
        ]);
    }
}
