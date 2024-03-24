<?php

namespace App\Services\WorkshopTemplates;

use App\Models\WorkshopTemplateWidget;

class WorkshopTemplatesWidgetService
{
    public function createWorkshopTemplateWidget(array $data)
    {
        return WorkshopTemplateWidget::create($data);
    }

    public function updateWorkshopTemplateWidget($workshop_template_widget, array $data)
    {
        return $workshop_template_widget->update($data);
    }

    public function deleteWorkshopTemplateWidget($workshop_template_widget)
    {
        return $workshop_template_widget->delete();
    }
}
