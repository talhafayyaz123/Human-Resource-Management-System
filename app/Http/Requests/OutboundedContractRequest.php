<?php

namespace App\Http\Requests;

use App\Models\Attachment;
use App\Models\ContractAttachment;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AttachmentSLARule;

class OutboundedContractRequest extends FormRequest
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
            'receiverType' => "required|in:customer,lead,partner",
            'receiverId' => "required|string",
            'status' => 'required|in:draft,approved,sent,ordered',
            'contractTypeId' => 'required|integer',
            'personInCharge' => 'required|exists:user_profile_data,id',
            'attachments' => 'sometimes|array',
            'attachments.*.contractAttachmentId' => 'nullable|int',
            'attachments.*.contractFields' => 'sometimes|array',
            'attachments.*.contractFields.*.id' => 'required|integer',
            'attachments.*.contractFields.*.key' => 'required',
            'attachments.*.contractFields.*.type' => 'required',
            'attachments.*.contractFields.*.value' => 'required',
            'attachments.*.id' => 'required|integer',
            'attachments.*.slaInfrastructureId' => new AttachmentSLARule,
            'attachments.*.slaInfrastructureUserSupport' => new AttachmentSLARule,
            'attachments.*.slaServiceTimeId' => new AttachmentSLARule,
            'attachments.*.slaLevelId' => new AttachmentSLARule,
            'attachments.*.isFixedPrice' => [new AttachmentSLARule, 'boolean'],
            'attachments.*.hourlyRate' => new AttachmentSLARule(1),
            'attachments.*.hoursPerYear' => new AttachmentSLARule(1),
            'attachments.*.totalPrice' => new AttachmentSLARule,
            'attachments.*.products' => 'nullable|array',
            'attachments.*.userId' => 'nullable|string',
            'attachments.*.startDate' => 'required|date',
            'attachments.*.terminationDate' => 'nullable|date',
            'attachments.*.signedDate' => 'nullable|date',
        ];
    }

    public function messages()
    {
        $customMessages = [];
        foreach ($this->input('attachments') as $attachmentIndex => $attachment) {
            $attachmentRecord = Attachment::where('id', $attachment['id'])->first();
            $customMessages["attachments.{$attachmentIndex}.startDate.required"] = "Start date of attachment {$attachmentRecord?->name} cannot be empty";
            foreach ($attachment['contractFields'] as $contractFieldIndex => $contractField) {
                $customMessages["attachments.{$attachmentIndex}.contractFields.{$contractFieldIndex}.value.required"]
                    = "{$contractField['key']} of attachment {$attachmentRecord?->name} cannot be empty";
            }
        }
        return $customMessages;
    }
}
