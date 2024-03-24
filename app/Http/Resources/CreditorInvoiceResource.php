<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CreditorInvoiceResource extends JsonResource
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
            'invoiceNumber' => $this->invoice_number,
            'receiverType' => $this->receiver_type,
            'invoiceType' => $this->invoice_type,
            'dueDate' => Carbon::parse($this->due_date)->format('Y-m-d'),
            'invoiceDate' => Carbon::parse($this->draft_status_change_date)->format('Y-m-d'),
            'startDate' => $this->start_date ? Carbon::parse($this->start_date)->format('Y-m-d') : null,
            'endDate' => $this->end_date ? Carbon::parse($this->end_date)->format('Y-m-d') : null,
            'invoicePeriod' => $this->paymentPeriod ? [
                'id' => $this->paymentPeriod->id,
                'name' => $this->paymentPeriod->name
            ] : null,
            'invoicePeriodName' => $this->paymentPeriod ? $this->paymentPeriod->name : "-",
            'termsOfPayment' => $this->termsOfPayment ? [
                'id' => $this->termsOfPayment->id,
                'name' => $this->termsOfPayment->name,
            ] : null,
            'termsOfPaymentName' => $this->termsOfPayment ? $this->termsOfPayment->name . " | " .
                $this->termsOfPayment->payment_terms : "-",
            'customNotesField' => $this->custom_notes_field,
            'paymentTerms' => $this->payment_terms ?? null,
            'totalAmountWithoutTax' => $this->total_amount_without_tax,
            'totalTaxAmount' => $this->total_tax_amount,
            'totalAmount' => $this->total_amount,
            'createdAt' => Carbon::parse($this->created_at)->toDateString(),
            'contactPerson' => $this->contact_person,
            'draftStatusChangeDate' => isset($this->draft_status_change_date) ? Carbon::parse($this->draft_status_change_date)->format('Y-m-d') : '',
            'customer' => [
                'id' => $this->company?->id,
                'companyName' => $this->company?->company_name,
                'vatId' => $this->company?->vat_id,
                'url' => $this->company?->url,
                'type' => $this->company?->type,
                'customerType' => $this->company?->customer_type,
                'companyNumber' => $this->company?->company_number,
                'state' => $this->company?->headQuarters?->state,
                'city' => $this->company?->headQuarters?->city,
                'country' => $this->company?->headQuarters?->country,
                'address' => $this->company?->headQuarters?->address_first,
                'code' => $this->company?->headQuarters?->zip,
                'notes' => $this->company?->notes,
                'status' => $this->company?->status,
                'expiryDate' => $this->company?->expiry_dt ? Carbon::parse($this->company?->expiry_dt)->toDateString() : '',
                'termsOfPayment' => $this->company?->terms_of_payment,
                'mergePdfsOnDefault' => $this->company?->merge_pdfs_on_default,
                'externalOrderNumber' => $this->company?->external_order_number

            ],
            'status' => $this->status,
            'userId' => $this->user_id,
        ];
    }
}