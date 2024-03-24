<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferRequestComponentResource extends JsonResource
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
            'type' => $this->type,
            'description' => $this->description,
            'estimatedWork' => $this->estimated_work_in_h ?? 0,
            'licenseReport' => $this->file ? [
                'id' => $this->file->id,
                'name' => $this->file->viewable_name,
                'size' => $this->file->storage_size,
                'storage_name' => $this->file->storage_name,
            ] : []
        ];
    }
}
