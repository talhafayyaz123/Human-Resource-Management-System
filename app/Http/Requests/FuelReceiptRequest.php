<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FuelReceiptRequest extends FormRequest
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
            'deliveryDate' => 'required|date',
            'actualMileage' => 'required|string',
            'product' => ['required', Rule::in(['diesel', 'electronic', 'gasoline'])],
            'quantity' => 'required|numeric',
            'unit' => 'required|string',
            'pricePerUnit' => 'required|numeric',
            'totalNetto' => 'required|numeric',
            'tax' => 'required|numeric',
            'totalBrutto' => 'required|numeric',
            'managerId' => "required|string",
            'fleetCarId' => "required"
        ];
    }
}
