<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ApplicationManagementServiceDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $ams=0;
        if(isset($this->customer) && !empty($this->customer) ){
         if($this->remaining_hours != 0){
            $ams=1;
         }
        }
        $attachment=0;
        if(isset($this->contractAttachment->attachment)){
            $prefix=$this->contractAttachment->attachment->prefix;
             if($prefix=='SW-PUS'){
                $attachment=1;
             }
        }
        
        return [
            'id'=>$this->id,
            'amsNumber' => $this->ams_number,
            'customerId' => $this->customer ? [
                'id' => $this->customer->id,
                'companyName' => $this->customer->company_name,
            ] : null,
            'serviceHoursYear' => $this->service_hours_year,
            'remainingHours' => $this->remaining_hours,
            'ams'=>$ams,
            'swPus'=>$attachment
            ];
    }
}
