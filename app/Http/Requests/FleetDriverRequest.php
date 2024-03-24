<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FleetDriverRequest extends FormRequest
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
            'carType' => 'required|in:rent_car,pool_car,leasing_car',
            'fleetCarId' => 'required_if:carType,leasing_car',
            'userId' => 'required',
            'vehicleClasses' => 'nullable|json',
        ];
    }
}
