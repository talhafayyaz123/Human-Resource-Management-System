<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetListRequest extends FormRequest
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
            'personalNumber' => 'nullable|string',
            'name' => 'required|string',
            'employeeId' => 'required|exists:user_profile_data,id',
            'location' => 'nullable|string',
            'teamId' => 'nullable',

        ];
    }
}
