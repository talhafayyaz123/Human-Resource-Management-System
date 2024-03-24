<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
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
            'name' => 'required|string',
            'softwareId' => 'required|integer',
            'template' => 'required',
            'contractTypeId' => 'required|integer',
            'prefix' => 'required|string',
            'version' => 'required|string',
            'allowToAddSlas' => 'required',
            'runtimeIn' => 'required|integer',
            'renewalPeriod' => 'required',
            'renewalPeriodIn' => 'required_if:renewalPeriod,1',
            'contractEndAt' => 'nullable|date',
            'noticePeriodIn' => 'required|integer',
            'rightOfTermination' => 'required',
            'contractFields' => 'nullable|array',
            'contractFields.*.key' => 'required|string',
            'contractFields.*.type' => 'required|string',
            'selectUser' => 'nullable|boolean',
            'addProductsToCustomerLicenses' => 'nullable|boolean'
        ];
    }
}
