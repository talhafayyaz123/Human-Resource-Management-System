<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectProtocolRequest extends FormRequest
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
            'date' => 'required',
            'recorderId' => 'required|string',
            'customerId' => 'required|string',
            'distributors' => 'required|array',
            'distributors.*' => 'required|string',
            'participants' => 'required|array',
            'participants.*' => 'required|string',
            'projectId' => 'required|integer',
            'protocolTypeId' => 'required|integer',
            'projectStatusId' => 'required|integer',
        ];
    }
}
