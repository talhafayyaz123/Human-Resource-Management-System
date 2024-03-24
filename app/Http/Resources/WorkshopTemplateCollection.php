<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;

class WorkshopTemplateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($workshop_template) {
                return [
                    "id" => $workshop_template->id,
                    "templateName" => $workshop_template->template_name,
                    "templateVersion" => $workshop_template->template_version,
                    "status" => $workshop_template->status,
                    "assignedProducts" => $workshop_template?->products?->map(function ($product) {
                        return [
                            'name' => $product->name,
                            'id' => $product->id,
                            'articleNumber' => $product->article_number,
                        ];
                    }),
                    'consultant' => ($workshop_template?->consultant?->first_name ?? '') . ' ' . ($workshop_template?->consultant?->last_name ?? ''),
                    "createdAt" => Carbon::parse($workshop_template->createdAt)->toDateString(),
                ];
            })
        ];
    }
}
