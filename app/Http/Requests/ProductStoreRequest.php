<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
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
        $rules = [
            'productTitle' => 'required|string',
            'longDescription' => 'nullable|string',
            'shortDescription' => 'required|string',
            'sellPrice' => 'required',
            'isVisibleForPartner' => 'required|boolean',
            'isVisibleForCustomer' => 'required|boolean',
            'authorId' => 'required',
            'productImages' => 'nullable|array',
            'products' => 'nullable|array',
            'products.*' => 'int|exists:products,id',
            'productsImages.*.base64' => 'required',
            'productsImages.*.size' => 'required',
            'productsImages.*.name' => 'required',
        ];

        if ($this->method() == 'PUT') {
            $rules['id'] = 'exists:product_stores,id';
            $rules['itemNumber'] = [
                'required',
                Rule::unique('product_stores', 'item_number')->ignore($this->route('product_store'))
                ];
        }
        return $rules;
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('product_store');
        return $data;
    }
}
