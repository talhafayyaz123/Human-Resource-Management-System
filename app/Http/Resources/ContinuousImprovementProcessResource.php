<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ContinuousImprovementProcessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $status = "pending";
        $approval_requests = $this->approvers()->count();
        $approved = $this->approvers()->where('status', 'approved')->count();
        $rejected = $this->approvers()->where('status', 'rejected')->count();
        if ($approval_requests > 0 && $approval_requests == $approved) {
            $status = "approved";
        } else if ($rejected > 0) {
            $status = "rejected";
        }
        return [
            "id" => $this->id,
            "processNumber" => $this->process_number,
            "date" => Carbon::parse($this->date)->toDateString(),
            "title" => $this->title,
            "requestNumber" => $this->request_number,
            "description" => $this->description,
            "suggestedSolution" => $this->suggested_solution,
            "userId" => $this->user_id,
            "affectedGroup" => $this->affectedGroup ? [
                'id' => $this->affectedGroup->id,
                'name' => $this->affectedGroup->name,
            ] : null,
            "files" => $this->files ?? [],
            "status" => $status
        ];
    }
}
