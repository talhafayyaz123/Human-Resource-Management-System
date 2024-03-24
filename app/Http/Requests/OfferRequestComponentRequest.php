<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequestComponentRequest extends FormRequest
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
            'components' => 'nullable|array',
            'components.*.id' => 'nullable',
            'components.*.type' => 'required|in:license,maintenance,service,application,hosting,cloud',
            'components.*.description' => 'required|string',
            'components.*.estimatedWork' => 'numeric|required_if:components.*.type,==,service',
            'components.*.licenseReport' => 'required_if:components.*.type,==,license',
        ];
    }
}
