<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModifyMyDataRequest extends FormRequest
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
            'process' => 'required|in:name-change,bank-account-change,change-of-health-insurance-company,change-of-address,change-of-tax-class,change-of-child-allowance',
            'reason' => 'required_if:process,name-change,change-of-health-insurance-company',
            'changeRequestData' => 'required|array',
        ];
    }
}
