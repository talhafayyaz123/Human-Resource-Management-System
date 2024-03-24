<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreReviewRequest extends FormRequest
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
            'productStoreId' => 'required|exists:product_stores,id',
            'title' => 'required|string',
            'review' => 'nullable|string',
            'ratting' => 'required|int',
          //  'images' => 'nullable|array',
            'images.*.base64' => 'required',
            'images.*.size' => 'required',
            'images.*.name' => 'required',
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'product_store_id' => $request['productStoreId'],
            'title' => $request['title'],
            'review' => $request['review'],
            'ratting' => $request['ratting'],
        ];
    }
}
