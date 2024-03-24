<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AttachmentResource extends JsonResource
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
            'software' => $this->software,
            'contractType' => $this->contractType,
            'prefix' => $this->prefix,
            'version' => $this->version,
            'template' => $this->template,
            'allowToAddSlas' => $this->allow_to_add_slas,
            'contractFields' => $this->contractFields,
            'runtimeIn' => $this->runtime_in,
            'renewalPeriod' => $this->renewal_period,
            'renewalPeriodIn' => $this->renewal_period_in,
            'contractEndAt' => $this->contract_end_at ? Carbon::parse($this->contract_end_at)->toDateString() : null,
            'noticePeriodIn' => $this->notice_period_in,
            'rightOfTermination' => $this->right_of_termination,
            'createdAt' => Carbon::parse($this->created_at)->toDateString(),
            'selectUser' => $this->is_select_user,
            'addProductsToCustomerLicenses' => $this->add_products_to_customer_licences ? true : false
        ];
    }
}
