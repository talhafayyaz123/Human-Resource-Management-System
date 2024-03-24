<?php

namespace App\Services\WorkshopTemplates;

use App\Models\WorkshopTemplate;
use App\Models\WorkshopTemplateCard;
use App\Models\UploadedFile;
use App\Services\WorkshopTemplates\WorkshopTemplatesCardsService;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class WorkshopTemplatesService
{

    use CustomHelper;

    public WorkshopTemplatesCardsService $workshopTemplatesCardsService;

    public function __construct(WorkshopTemplatesCardsService $workshopTemplatesCardsService)
    {
        $this->workshopTemplatesCardsService = $workshopTemplatesCardsService;
    }

    /**
     * Create a new workshop template.
     *
     * @param  array  $data
     * @return \App\Models\WorkshopTemplate
     */
    public function createWorkshopTemplate(array $data)
    {
        $workshop_template = WorkshopTemplate::create($data);
        $workshop_template->products()->sync($data["assigned_products"]);
        return $workshop_template;
    }

    /**
     * Update the specified workshop template.
     *
     * @param  \App\Models\WorkshopTemplate  $workshop_template
     * @param  array  $data
     * @return \App\Models\WorkshopTemplate
     */
    public function updateWorkshopTemplate(WorkshopTemplate $workshop_template, array $data)
    {
        $workshop_template->update($data);
        $workshop_template->products()->sync($data["assigned_products"]);
        $this->syncCards(isset($data['cards']) ? $data['cards'] : [], $workshop_template->id); // updates the cards for this workshop template
        return $workshop_template;
    }

    /**
     * Delete the specified workshop template.
     *
     * @param  \App\Models\WorkshopTemplate $workshop_template
     * @return void
     */
    public function deleteWorkshopTemplate(WorkshopTemplate $workshop_template)
    {
        $files = $workshop_template->files;
        Storage::disk('local')->delete($files->map(fn ($file) => 'workshop_templates/files/' . $file->storage_name)->toArray());
        $files->each->delete();
        $workshop_template->delete();
    }

    /**
     * Updates or creates a card related to this workshop template
     * Calls the 'syncRows' of 'workshopTemplatesCardsService', which updates or creates rows 
     * @param {$cards} the cards array for this workshop template
     */
    public function syncCards($cards, $id)
    {
        foreach ($cards as $card) {
            $template_card = WorkshopTemplateCard::updateOrCreate(
                ["id" => isset($card['id']) ? $card['id'] : '', "workshop_template_id" => $id],
                [...$this->convertKeysToSnakeCase(collect($card)), "workshop_template_id" => $id]
            );
            $this->workshopTemplatesCardsService->syncRows(isset($card['rows']) ? $card['rows'] : [], $template_card->id);
        }
    }

    /**
     * uploads the files to local storage by calling the uploadFiles method on workshopTemplateService
     * @param $request
     */
    public function uploadFiles($request)
    {
        $workshop_template = WorkshopTemplate::findOrFail($request['id']);
        foreach ((isset($request['files']) ? $request['files'] : []) as $file) {
            $original_name = $file->getClientOriginalName();
            $size = $file->getSize();
            $file_name_to_store =
                $workshop_template->id . '__' . $original_name;
            Storage::disk('local')->putFileAs('workshop_templates/files/', $file, $file_name_to_store);
            $uploaded_file = new UploadedFile;
            $uploaded_file->storage_name = $file_name_to_store;
            $uploaded_file->viewable_name = $original_name;
            $uploaded_file->storage_size =  $size;
            $uploaded_file->fileable()->associate($workshop_template);
            $uploaded_file->save();
        }
        return response()->json([
            "success" => true,
            "message" => "Files uploaded successfully",
            "files" => $workshop_template?->files?->map(fn ($file) => [
                'id' => $file->id,
                'storage_name' => $file->storage_name,
                'name' => $file->viewable_name,
                'size' => $file->storage_size ?? null,
            ]) ?? [],
        ]);
    }

    /**
     * Deletes a file based on the id
     * @param {$request} received request
     */
    public function deleteFile($id)
    {
        $file = UploadedFile::findOrFail($id);
        $path = 'workshop_templates/files/' . $file->storage_name;
        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }
        $file->delete();
        return response()->json([
            "success" => true,
            "message" => "File deleted successfully"
        ]);
    }

    /**
     * exports files and returns json
     */
    public function exportFile($request)
    {
        $response = Http::withToken($request->bearerToken())
            ->accept('multipart/form-data')
            ->attach(
                'upload_document',
                fopen('../storage/app/workshop_templates/files/' . $request->filename, 'r')
            )
            ->post(config('app.VITE_TEMPLATE_PARSER_URL') . '/process-template', [
                'data' => $request->data,
            ]);
        $response->throw();
        return response()->json([
            "success" => true,
            "file" => $request->filename,
            "data" => $response->json()
        ]);
    }
}
