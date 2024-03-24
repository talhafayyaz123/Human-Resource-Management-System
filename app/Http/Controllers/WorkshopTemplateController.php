<?php

namespace App\Http\Controllers;

use App\Models\WorkshopTemplate;
use App\Http\Resources\WorkshopTemplateCollection;
use App\Http\Resources\WorkshopTemplateResource;
use App\Services\WorkshopTemplates\WorkshopTemplatesService;
use App\Http\Requests\WorkshopTemplatesRequest;
use App\Traits\CustomHelper;
use App\Utils\Logger;
use Illuminate\Http\Request;

class WorkshopTemplateController extends Controller
{
    use CustomHelper;

    public WorkshopTemplatesService $workshopTemplateService;

    public function __construct(WorkshopTemplatesService $workshopTemplateService)
    {
        $this->workshopTemplateService = $workshopTemplateService;
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
        $model = new WorkshopTemplate();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->orderBy('created_at')->paginate($per_page);
        
        return new WorkshopTemplateCollection($model);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkshopTemplatesRequest $request)
    {
        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $workshop_template = $this->workshopTemplateService->createWorkshopTemplate($data);

        return response()->json([
            "success" => true,
            "message" => "Record Created sucessfully",
            "id" => $workshop_template->id
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
        $workshop_template = WorkshopTemplate::findOrFail($id);
        return new WorkshopTemplateResource($workshop_template);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WorkshopTemplatesRequest $request, $id)
    {
        $workshop_template = WorkshopTemplate::findOrFail($id);

        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect(($validated_data)));

        $this->workshopTemplateService->updateWorkshopTemplate($workshop_template, $data);

        return response()->json([
            "success" => true,
            "message" => "Record Updated sucessfully",
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
        $workshop_template = WorkshopTemplate::findOrFail($id);
        $this->workshopTemplateService->deleteWorkshopTemplate($workshop_template);
        return response()->json([
            "success" => true,
            "message" => "Record deleted successfully"
        ]);
    }

    /**
     * uploads the files to local storage by calling the uploadFiles method on workshopTemplateService
     * @param $request
     */
    public function uploadFiles(Request $request)
    {
        return $this->workshopTemplateService->uploadFiles($request->all());
    }

    /**
     * deletes the file by calling the deleteFile method on workshopTemplateService
     * @param $id
     */
    public function deleteFile($id)
    {
        return $this->workshopTemplateService->deleteFile($id);
    }

    /** calls the ;exportFiles' service on workshopTemplateService */
    public function exportFile(Request $request)
    {
        return $this->workshopTemplateService->exportFile($request);
    }
}
