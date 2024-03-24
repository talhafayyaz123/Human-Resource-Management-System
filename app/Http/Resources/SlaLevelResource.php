<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SlaLevelResource extends JsonResource
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
            'name' => $this->name,
            'reactionTimeUrgent' => $this->reaction_time_urgent,
            'reactionTimeHigh' => $this->reaction_time_high,
            'reactionTimeLow' => $this->reaction_time_low,
            'factor' => $this->factor,
        ];
    }
}
