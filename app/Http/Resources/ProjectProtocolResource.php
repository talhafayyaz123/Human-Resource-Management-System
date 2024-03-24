<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ProjectProtocolResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "date" => Carbon::parse($this->date)->toDateString(),
            "recorderId" => $this->recorder_id,
            "customer" => $this->customer ? [
                'id' => $this->customer->id,
                'companyName' => $this->customer->company_name,
                'vatId' => $this->customer->vat_id,
                'url' => $this->customer->url,
                'type' => $this->customer->type,
                'customerType' => $this->customer->customer_type,
                'companyNumber' => $this->customer->company_number,
                'state' => "",
                'city' => "",
                'country' => "",
                'address' => "",
                'notes' => $this->customer->notes,
                'status' => $this->customer->status,
                'expiryDate' => $this->customer->expiry_dt ? Carbon::parse($this->customer->expiry_dt)->toDateString() : '',
                'termsOfPayment' => $this->customer->terms_of_payment,
                'contactReportSources' => $this->customer?->contactReportSources
            ] : null,
            "distributors" => $this->distributors,
            "participants" => $this->participants,
            "protocolType" => $this->protocolType ? [
                "id" => $this->protocolType->id,
                "name" => $this->protocolType->name,
            ] : null,
            "projectStatus" => $this->projectStatus ? [
                "id" => $this->projectStatus->id,
                "name" => $this->projectStatus->name,
            ] : null,
            "project" => $this->project ? [
                'id' => $this->project->id,
                'projectNumber' => $this->project->project_number,
                'name' => $this->project->name,
                'status' => $this->project->status,
                "plannedStartDate" => $this->project->planned_start_date,
                "actualStartDate" => $this->project->actual_start_date,
                "earliestStartDate" => $this->project->earliest_start_date,
                "actualFinishedDate" => $this->project->actual_finished_date,
                "expectedFinishedDate" => $this->project->expected_finished_date,
                "plannedFinishedDate" => $this->project->planned_finished_date,
                "estimatedTime" => $this->project->estimated_time,
                "neededTime" => $this->project->needed_time,
                "companyId" => $this->project->company_id,
                "userId" => $this->project->user_id,
                "description" => $this->project->description,
            ] : null
        ];
    }
}
