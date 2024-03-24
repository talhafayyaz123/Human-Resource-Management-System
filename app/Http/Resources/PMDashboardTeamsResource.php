<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PMDashboardTeamsResource extends JsonResource
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
            'id' => $this->team->id,
            "name" => $this->team->name,
        ];
    }
}
