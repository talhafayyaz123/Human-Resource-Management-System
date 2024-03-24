<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeCheckingResource extends JsonResource
{
    public function toArray($request)
    {
        $additionalInfo = [];
        $module_type = null;
        if ($this->module_type == 'App\Models\TicketComment') {
            $module_type = 'ticket';
            $additionalInfo = [
                "ticketNumber" => $this->module->ticket->ticket_number
            ];
        } elseif ($this->module_type == 'App\Models\Task') {
            $module_type = 'task';
            $additionalInfo = [
                "project" => $this->module->milestone->project,
                "milestone" => $this->module->milestone
            ];
        } elseif ($this->module_type == 'App\Models\ApplicationManagementService') {
            $module_type = 'ams';
        } elseif ($this->module_type == 'App\Models\TravelExpense') {
            $module_type = 'travel-expense';
            $additionalInfo = [
                "travelNumber" => $this->module?->travel_number,
            ];
        } else {
            $module_type = $this->module_type;
            $additionalInfo = [
                "project" => $this->project
            ];
        }
        return [
            'id' => $this->id,
            'date' => $this->date,
            'type' => $module_type,
            "moduleId" => $this->module_id ?? null,
            "internalNote" => $this->internal_note,
            'userName' => $this->user?->full_name,
            'userId' => $this->user_id,
            'companyName' => $this->company?->company_name,
            'company' => $this->company ? [
                'id' => $this->company->id,
                'companyName' => $this->company->company_name,
            ] : null,
            'companyId' => $this->company_id,
            'description' => $this->description,
            'isGoodwill' => $this->is_goodwill,
            'quantity' => $this->time,
            'status' => $this->time_checking_status,
            'additionalInfo' => $additionalInfo
        ];
    }
}