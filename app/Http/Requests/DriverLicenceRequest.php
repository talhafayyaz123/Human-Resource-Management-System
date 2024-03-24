<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverLicenceRequest extends FormRequest
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
            'fleetDriverId' => ['required'],
            'checkType' => ['required', 'in:online,personal'],
            'licenceValidUntil' => ['required', 'date'],
            'managerId' => ['required', 'string'],
            'vehicleClasses' => ['required', 'array'],
            'createdAt' => ['required', 'date']
        ];
    }
}
