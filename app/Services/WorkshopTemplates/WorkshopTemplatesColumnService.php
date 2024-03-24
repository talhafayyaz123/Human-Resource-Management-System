<?php

namespace App\Services\WorkshopTemplates;

use App\Models\WorkshopTemplateColumn;
use App\Models\WorkshopTemplateWidget;
use App\Traits\CustomHelper;

class WorkshopTemplatesColumnService
{

    use CustomHelper;

    public function createWorkshopTemplateColumn(array $data)
    {
        return WorkshopTemplateColumn::create($data);
    }

    public function updateWorkshopTemplateColumn($workshop_template_column, array $data)
    {
        return $workshop_template_column->update($data);
    }

    public function deleteWorkshopTemplateColumn($workshop_template_column)
    {
        return $workshop_template_column->delete();
    }

    /**
     * Updates or creates a widgets related to this workshop template column
     * @param {$widgets} the widgets array for this workshop template column
     */
    public function syncWidgets($widgets, $id)
    {
        foreach ($widgets as $widget) {
            WorkshopTemplateWidget::updateOrCreate(
                ["id" => isset($widget['id']) ? $widget['id'] : '', "workshop_templates_column_id" => $id],
                [...$this->convertKeysToSnakeCase(collect($widget)), "workshop_templates_column_id" => $id]
            );
        }
    }
}
