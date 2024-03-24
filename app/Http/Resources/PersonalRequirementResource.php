<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PersonalRequirementResource extends JsonResource
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
            'id' => $this->id,
            'department' => $this->personalRequest?->department?->name,
            'team' => $this->personalRequest?->team?->name,
            'requester' => $this->personalRequest?->requester?->full_name,
            'job' => $this->personalRequest?->job?->j_title,
            'contractType' => $this->personalRequest?->contractType?->name,
            'startDate' => $this->personalRequest?->start_date ? Carbon::parse($this->personalRequest?->start_date)->toDateString() : null,
            'status' => $this->status,
        ];
    }
}
