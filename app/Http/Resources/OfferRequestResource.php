<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OfferRequestComponentResource;

class OfferRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge([
            'id' => $this->id,
            'offerRequestNumber' => $this->offer_request_number,
            'receiverType' => $this->receiver_type,
            'receiver' => $this->receiver ? [
                'id' => $this->receiver->id,
                'companyName' => $this->receiver->company_name
            ] : null,
            'text' => $this->text,
            'groupedBy' => $this->grouped_by,
            'requestStatus' => $this->request_status,
            'createdBy' => $this->employee ? [
                'id' => $this->employee->id,
                'userId' => $this->employee->user_id,
                'employee' => $this->employee->full_name
            ] : null,
            'projectId' => $this->project ? [
                'id' => $this->project->id,
                'projectNumber' => $this->project->project_number
            ] : null,
            'status' => $this->status,
            'contactPerson' => $this->contact_person,
            'components' => OfferRequestComponentResource::collection($this->components)
        ]);
    }
}