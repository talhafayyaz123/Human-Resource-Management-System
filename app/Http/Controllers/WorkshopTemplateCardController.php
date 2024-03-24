<?php

namespace App\Http\Controllers;

use App\Utils\Logger;
use Illuminate\Http\Request;
use App\Http\Requests\WorkshopTemplatesCardRequest;
use App\Models\WorkshopTemplate;
use App\Models\WorkshopTemplateCard;
use App\Services\WorkshopTemplates\WorkshopTemplatesCardsService;
use App\Http\Resources\WorkshopTemplateCardResource;
use App\Traits\CustomHelper;

class WorkshopTemplateCardController extends Controller
{

    use CustomHelper;

    public WorkshopTemplatesCardsService $workshopTemplatesCardsService;

    public function __construct(WorkshopTemplatesCardsService $workshopTemplatesCardsService)
    {
        $this->workshopTemplatesCardsService = $workshopTemplatesCardsService;
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
    public function store(WorkshopTemplatesCardRequest $request)
    {

        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect($validated_data));

        $workshop_templates_card = $this->workshopTemplatesCardsService->createWorkshopTemplateCard($data);

        return response()->json([
            "success" => true,
            "message" => "Record created successfully",
            "id" => $workshop_templates_card->id
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
        $workshop_templates_card = WorkshopTemplateCard::findOrFail($id);
        return new WorkshopTemplateCardResource($workshop_templates_card);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WorkshopTemplatesCardRequest $request, $id)
    {
        $workshop_templates_card = WorkshopTemplateCard::findOrFail($id);

        $validated_data = $request->validated();

        $data = $this->convertKeysToSnakeCase(collect($validated_data));

        $this->workshopTemplatesCardsService->updateWorkshopTemplateCard($workshop_templates_card, $data);

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
        $workshop_templates_card = WorkshopTemplateCard::findOrFail($id);
        $this->workshopTemplatesCardsService->deleteWorkshopTemplateCard($workshop_templates_card);

        return response()->json([
            "success" => true,
            "message" => "Record deleted successfully"
        ]);
    }
}
