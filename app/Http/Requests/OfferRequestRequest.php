<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\OfferRequestComponentRequest;

class OfferRequestRequest extends FormRequest
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
        return array_merge([
            'offerRequestNumber' => 'sometimes',
            'receiverType' => 'required|in:customer,lead',
            'receiverId' => 'required|uuid',
            'text' => 'required|string',
            'groupedBy' => 'required|in:nothing,category',
            'createdBy' => 'required|integer',
            'projectId' => 'nullable|integer',
            'status' => 'required|in:open,approved,declined',
            'requestStatus' => 'required|in:draft,open,pending,rejected,approved',
            'contactPerson' => 'nullable|string'
        ], (new OfferRequestComponentRequest())->rules());
    }
}