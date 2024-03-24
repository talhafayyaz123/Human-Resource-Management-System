<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkshopTemplatesRequest extends FormRequest
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
            "id" => "sometimes",
            "templateName" => "required|string",
            "templateVersion" => "required|string",
            "status" => "in:draft,stable",
            "consultantId" => "integer",
            "assignedProducts" => "array",
            "cards" => "sometimes|array",
            "cards.*.id" => "sometimes",
            "cards.*.workshopTemplateId" => "sometimes|integer",
            "cards.*.configuration" => "sometimes|array",
            "cards.*.rows" => "sometimes|array",
            "cards.*.rows*.*.id" => "sometimes",
            "cards.*.rows.*.workshopTemplatesCardId" => "sometimes|integer",
            "cards.*.rows.*.configuration" => "sometimes|array",
            "cards.*.rows.*.columns" => "sometimes|array",
            "cards.*.rows.*.columns.*.id" => "sometimes",
            "cards.*.rows.*.columns.*.workshopTemplatesRowId" => "sometimes|integer",
            "cards.*.rows.*.columns.*.configuration" => "sometimes|array",
            "cards.*.rows.*.columns.*.widgets" => "sometimes|array",
            "cards.*.rows.*.columns.*.widgets.*.id" => "sometimes",
            "cards.*.rows.*.columns.*.widgets.*.type" => "sometimes|in:heading,paragraph,checkbox,radio-button,text-input,number-input,table,wysiwyg,select",
            "cards.*.rows.*.columns.*.widgets.*.workshopTemplatesColumnId" => "sometimes|integer",
            "cards.*.rows.*.columns.*.widgets.*.configuration" => "sometimes|array"
        ];
    }
}
