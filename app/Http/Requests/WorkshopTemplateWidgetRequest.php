<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkshopTemplateWidgetRequest extends FormRequest
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
            "type" => "required|in:heading,paragraph,checkbox,radio-button,text-input,number-input,table,wysiwyg,select",
            "workshopTemplatesColumnId" => "required|integer",
            "configuration" => "array"
        ];
    }
}
