<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditorInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'objKeys.COMPANY_NAME' => ['required', 'string'],
            'objKeys.invoiceType' => 'required|string',
            'objKeys.invoicePeriod' => 'required',
            'objKeys.termsOfPayment' => 'required',
            'objKeys.notes' => 'nullable|string',
            'objKeys.ACCOUNTING_NUMBER' => 'required',
            'objKeys.ACCOUNTING_DUE_DATE' => 'required|string',
            'objKeys.ACCOUNTING_NET_AMOUNT' => 'required',
            'objKeys.ACCOUNTING_TOTAL_AMOUNT' => 'required',
            'objKeys.ACCOUNTING_TAX_AMOUNT' => 'required',
            'objKeys.userId' => 'required',
            "objKeys.ACCOUNTING_DATEV_FISCALYEAR" => "required",
            "objKeys.ACCOUNTING_DATEV_POSTINGPROPOSAL_CREATED" => "required",
            "objKeys.receiverType" => "required|in:customer,partner",
            'objKeys.ACCOUNTING_DATE' => "required:string",
            'objKeys.paymentTerms' => 'nullable|string',
            'objKeys.contactPerson' => 'nullable|string',
            'objKeys.customNotesField' => 'nullable|string',
        ];
    }
}
