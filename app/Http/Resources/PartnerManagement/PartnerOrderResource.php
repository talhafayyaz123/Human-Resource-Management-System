<?php

namespace App\Http\Resources\PartnerManagement;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PartnerOrderResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'orderNumber' => $this->order_number,
            'partnerId' => $this->partner_id,
            'partner' => [
                'id' => $this->partner->id,
                'companyName' => $this->partner?->company_name,
                'companyNumber' => $this->partner?->company_number
            ],
            'receiver' => [
                'id' => $this->company->id,
                'companyName' => $this->company?->company_name,
                'companyNumber' => $this->company?->company_number,
                'state' => $this->company->headQuarters?->state,
                'city' => $this->company->headQuarters?->city,
                'country' => $this->company->headQuarters?->country,
                'address' => $this->company->headQuarters?->address_first,
                'code' => $this->company->headQuarters?->zip,
            ],
            'termOfPayment' => [
                'id' => $this->termOfPayment?->id,
                'name' => $this->termOfPayment?->name,
            ],
            'project' => [
                'id' => $this->project?->id,
                'name' => $this->project?->name,
            ],
            'partnerName' => $this->partner?->company_name,
            'receiverId' => $this->receiver_id,
            'receiverName' => $this->company?->company_name,
            'contactPerson' => $this->contact_person,
            'contactPartnerPerson' => $this->partner_contact_person,
            'createdBy' => $this->created_by,
            'createdAt' => Carbon::parse($this->created_at)->toDateString(),
            'termId' => $this->term_id,
            'termName' => $this->termOfPayment?->name,
            'paymentTerms' => $this->payment_terms,
            'projectId' => $this->project_id,
            'projectName' => $this->project?->name,
            'expiryDate' => $this->expiry_date,
            'status' => $this->status,
            'externalOrderNumber' => $this->external_order_number,
            'identifier' => $this->identifier,
            'nettoTotal' =>  $this->orderProducts->sum('total_netto'),
            'partnerPriceCheck' => $this->orderProducts->where('type', 'partner_price_list')->count() > 0 ? true : false,
            'ownPriceCheck' => $this->orderProducts->where('type', 'own_price_list')->count() > 0 ? true : false,
        ];

        if (isset($request['show'])) {
            $data['products'] = PartnerOrderProductResource::collection($this->orderProducts);
        }

        return $data;
    }
}
