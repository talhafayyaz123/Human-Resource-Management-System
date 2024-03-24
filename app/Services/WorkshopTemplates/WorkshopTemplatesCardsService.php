<?php

namespace App\Services\WorkshopTemplates;

use App\Models\WorkshopTemplateCard;
use App\Models\WorkshopTemplateRow;
use App\Services\WorkshopTemplates\WorkshopTemplatesRowService;
use App\Traits\CustomHelper;

class WorkshopTemplatesCardsService
{

    use CustomHelper;

    public WorkshopTemplatesRowService $workshopTemplatesRowService;

    public function __construct(WorkshopTemplatesRowService $workshopTemplatesRowService)
    {
        $this->workshopTemplatesRowService = $workshopTemplatesRowService;
    }

    public function createWorkshopTemplateCard(array $data)
    {
        return WorkshopTemplateCard::create($data);
    }

    public function updateWorkshopTemplateCard($workshop_template_card, array $data)
    {
        return $workshop_template_card->update($data);
    }

    public function deleteWorkshopTemplateCard($workshop_template_card)
    {
        return $workshop_template_card->delete();
    }

    /**
     * Updates or creates a rows related to this workshop template card
     * Calls the 'syncColumns' of 'workshopTemplatesRowService', which updates or creates rows 
     * @param {$rows} the rows array for this workshop template card
     */
    public function syncRows($rows, $id)
    {
        foreach ($rows as $row) {
            $template_row = WorkshopTemplateRow::updateOrCreate(
                ["id" => isset($row['id']) ? $row['id'] : '', "workshop_templates_card_id" => $id],
                [...$this->convertKeysToSnakeCase(collect($row)), "workshop_templates_card_id" => $id]
            );
            $this->workshopTemplatesRowService->syncColumns(isset($row['columns']) ? $row['columns'] : [], $template_row->id);
        }
    }
}
