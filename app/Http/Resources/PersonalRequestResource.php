<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PersonalRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $status = 'pending';

        $personal_requirements_count = $this->personalRequirements()->count();

        if ($this->personalRequirements()->where('status', 'approved')->count() == $personal_requirements_count) {
            $status = 'approved';
        } else if ($this->personalRequirements()->where('status', 'rejected')->count() > 0) {
            $status = 'rejected';
        }

        return [
            'id' => $this->id,
            'requester' => $this->requester ? [
                'id' => $this->requester->id,
                'userId' => $this->requester->user_id,
                'employee' => $this->requester->full_name,
            ] : null,
            'department' => $this->department ? [
                'id' => $this->department->id,
                'name' => $this->department->name,
            ] : null,
            'team' => $this->team ? [
                'id' => $this->team->id,
                'name' => $this->team->name,
            ] : null,
            'job' => $this->job ? [
                'id' => $this->job->id,
                'jobName' => $this->job->j_title,
            ] : null,
            'location' => $this->location ? [
                'id' => $this->location->id,
                'name' => $this->location->name,
            ] : null,
            'contractType' => $this->contractType ? [
                'id' => $this->contractType->id,
                'name' => $this->contractType->name,
            ] : null,
            'timeModel' => $this->timeModel->map(function ($item) {
                return [
                    'days' => [$item->day],
                    'numOfHours' => $item->working_hours,
                ];
            }),
            'startDate' => Carbon::parse($this->start_date)->toDateString(),
            'status' => $status
        ];
    }
}
