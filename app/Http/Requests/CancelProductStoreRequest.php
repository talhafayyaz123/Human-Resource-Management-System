<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CancelProductStoreRequest extends FormRequest
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
        $rules = [
            'customerId' => 'required',
            'partnerId' => 'required',
            'reason' => 'required|string',
            'storeEntryId' => 'required|array',
        ];

        if ($this->method() == 'PUT') {
       //     $rules['id'] = 'exists:cancelation_requests,id';
        }
        return $rules;
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('cancelation_requests');
        return $data;
    }
}
