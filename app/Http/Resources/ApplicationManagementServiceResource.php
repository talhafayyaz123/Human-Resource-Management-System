<?php

namespace App\Http\Resources;

use App\Models\ContractFieldProduct;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ApplicationManagementServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'amsNumber' => $this->ams_number,
            'customerId' => $this->customer ? [
                'id' => $this->customer->id,
                'companyName' => $this->customer->company_name,
                'vatId' => $this->customer->vat_id,
                'url' => $this->customer->url,
                'type' => $this->customer->type,
                'customerType' => $this->customer->customer_type,
                'companyNumber' => $this->customer->company_number,
                'state' => "",
                'city' => "",
                'country' => "",
                'address' => "",
                'notes' => $this->customer->notes,
                'status' => $this->customer->status,
                'expiryDate' => $this->customer->expiry_dt ? Carbon::parse($this->customer->expiry_dt)->toDateString() : '',
                'termsOfPayment' => $this->customer->terms_of_payment,
                'contactReportSources' => $this->customer?->contactReportSources
            ] : null,
            'serviceHoursYear' => $this->service_hours_year,
            'remainingHours' => $this->remaining_hours,
            'hourlyRate' => $this->hourly_rate,
            'monthlyAmount' => $this->monthly_amount,
            'attachment' => $this->contractAttachment ? [
                'id' => $this->contractAttachment->id,
                'name' => $this->contractAttachment->attachment->name,
                'prefix' => $this->contractAttachment->attachment->prefix,
                'version' => $this->contractAttachment->attachment->version,
                'allowToAddSlas' => $this->contractAttachment->attachment->allow_to_add_slas,
                'software' => $this->contractAttachment->attachment->software ? [
                    'id' => $this->contractAttachment->attachment->software->id,
                    'name' => $this->contractAttachment->attachment->software->name,
                ] : null,
                'slaInfrastructureId' => $this->contractAttachment->slaInfrastructure ? [
                    'id' => $this->contractAttachment->slaInfrastructure->id,
                    'name' => $this->contractAttachment->slaInfrastructure->name,
                    'category' => $this->contractAttachment->slaInfrastructure->category
                ] : null,
                'slaInfrastructureUserSupport' => $this->contractAttachment->sla_infrastructure_user_support,
                'slaServiceTimeId' => $this->contractAttachment->slaServiceTime ? [
                    'id' => $this->contractAttachment->slaServiceTime->id,
                    'name' => $this->contractAttachment->slaServiceTime->name,
                    'factor' => $this->contractAttachment->slaServiceTime->factor,
                ] : null,
                'slaLevelId' => $this->contractAttachment->slaLevel ? [
                    'id' => $this->contractAttachment->slaLevel->id,
                    'name' => $this->contractAttachment->slaLevel->name,
                    'reactionTimeUrgent' => $this->contractAttachment->slaLevel->reaction_time_urgent,
                    'reactionTimeHigh' => $this->contractAttachment->slaLevel->reaction_time_high,
                    'reactionTimeLow' => $this->contractAttachment->slaLevel->reaction_time_low,
                ] : null,
            ] : null,
            'softwareId' => $this->software_id ?? '',
        ];
        if ($request['show']) {
            $data['attachment']['products'] = $this->getProductsInfo();
        }

        return $data;
    }


    private function getProductsInfo()
    {
        $contractProducts = $this->contractAttachment?->attachmentProducts?->map(function ($product) {
            return [
                'productName' => $product->product?->name,
                'quantity' => $product->quantity,
            ];
        });
        $contractFieldsIds = $this->contractAttachment?->selectedContractFields?->pluck('id')->toArray();
        if ($contractFieldsIds) {
            $contractFieldProducts = ContractFieldProduct::whereIn('contract_field_id', $contractFieldsIds)->get()
                ->map(function ($product) {
                    return [
                        'productName' => $product->product?->name,
                        'quantity' => $product->quantity
                    ];
                });

            return $contractProducts->merge($contractFieldProducts);
        }

        return $contractProducts;
    }
}
