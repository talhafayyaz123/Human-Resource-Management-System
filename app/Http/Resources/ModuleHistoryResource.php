<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ModuleHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'moduleAction' => $this->module_action,
            'dateAndTime' => $this->created_at->setTimezone('UTC')->format('Y-m-d'),
            'time' => $this->created_at->setTimezone('UTC')->format('H:i:s'),
            'userName' => $this->userProfileData?->full_name,
            'userId' => $this->user_id,
            'isEloRequestSent' => $this->is_elo_request_sent ? true : false
        ];
    }
}
