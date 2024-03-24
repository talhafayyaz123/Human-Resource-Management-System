<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonalRequestRequest extends FormRequest
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
            'userId' => 'required|integer',
            'departmentId' => 'required|string',
            'teamId' => 'required|string',
            'jobId' => 'required|integer',
            'locationId' => 'required|string',
            'contractTypeId' => 'required|integer',
            'timeModel' => 'required|array',
            'timeModel.*.day' => 'required|string',
            'timeModel.*.numOfHours' => 'required|integer',
            'startDate' => 'required|date'
        ];
    }
}
