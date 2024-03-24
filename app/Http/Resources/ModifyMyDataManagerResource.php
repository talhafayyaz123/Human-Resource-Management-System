<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ModifyMyDataManagerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'requestId' => $this->modifyMyData?->id,
            'process' => $this->modifyMyData?->process,
            'reason' => $this->modifyMyData?->reason,
            'employee' => $this->modifyMyData?->userProfileData?->full_name,
            'administrator' => $this->modifyMyData?->changedManager?->employee?->full_name ?? '',
            'personnelNumber' => $this->modifyMyData?->userProfileData?->jobData?->personal_number,
            'status' => $this->modifyMyData?->status,
            'createdAt' => $this->modifyMyData?->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
