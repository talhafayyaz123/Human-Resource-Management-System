<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CIPApprovalResource extends JsonResource
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
            'id' => $this->pivot->id,
            'requestNumber' => $this->request_number,
            'processNumber' => $this->process_number,
            'title' => $this->title,
            'date' => Carbon::parse($this->date)->toDateString(),
            'affectedGroup' => $this->affectedGroup?->name ?? '',
            'status' => $this->pivot->status,
            'approverType' => $this->pivot->approver_type
        ];
    }
}
