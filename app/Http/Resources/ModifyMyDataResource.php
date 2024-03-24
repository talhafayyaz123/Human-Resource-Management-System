<?php namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ModifyMyDataResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'process' => $this->process,
            'reason' => $this->reason,
            'changeRequestData' => $this->change_request_data,
            'personInCharge' => $this->changedManager?->employee?->full_name,
            'status' => $this->status,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'statusChangedAt' => $this->changedManager?->changed_at ?? '-',
        ];
    }
}
