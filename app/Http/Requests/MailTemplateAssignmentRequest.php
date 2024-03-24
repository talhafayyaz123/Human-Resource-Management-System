<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailTemplateAssignmentRequest extends FormRequest
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
            'modules' => 'required|array',
            'modules.*.module' => 'required|string',
            'modules.*.mailTemplateId' => 'nullable|string',
            'modules.*.cc' => 'nullable|string',
            'modules.*.bcc' => 'nullable|string',
            'modules.*.senderMail' => 'nullable|string',
        ];
    }
}
