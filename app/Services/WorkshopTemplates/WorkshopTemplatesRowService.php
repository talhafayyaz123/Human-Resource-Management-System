<?php

namespace App\Services\WorkshopTemplates;

use App\Models\WorkshopTemplateRow;
use App\Models\WorkshopTemplateColumn;
use App\Services\WorkshopTemplates\WorkshopTemplatesColumnService;
use App\Traits\CustomHelper;

class WorkshopTemplatesRowService
{
    use CustomHelper;

    public WorkshopTemplatesColumnService $workshopTemplatesColumnService;

    public function __construct(WorkshopTemplatesColumnService $workshopTemplatesColumnService)
    {
        $this->workshopTemplatesColumnService = $workshopTemplatesColumnService;
    }

    public function createWorkshopTemplateRow(array $data)
    {
        return WorkshopTemplateRow::create($data);
    }

    public function updateWorkshopTemplateRow($workshop_template_row, array $data)
    {
        return $workshop_template_row->update($data);
    }

    public function deleteWorkshopTemplateRow($workshop_template_row)
    {
        return $workshop_template_row->delete();
    }

    /**
     * Updates or creates a column related to this workshop template row
     * Calls the 'syncWidgets' of 'workshopTemplatesColumnService', which updates or creates widgets 
     * @param {$columns} the columns array for this workshop template row
     */
    public function syncColumns($columns, $id)
    {
        foreach ($columns as $column) {
            $template_column = WorkshopTemplateColumn::updateOrCreate(
                ["id" => isset($column['id']) ? $column['id'] : '', "workshop_templates_row_id" => $id],
                [...$this->convertKeysToSnakeCase(collect($column)), "workshop_templates_row_id" => $id]
            );
            $this->workshopTemplatesColumnService->syncWidgets(isset($column['widgets']) ? $column['widgets'] : [], $template_column->id);
        }
    }
}
