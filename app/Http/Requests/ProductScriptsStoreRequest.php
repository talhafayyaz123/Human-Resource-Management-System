<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductScriptsStoreRequest extends FormRequest
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
            'title' => 'required|string',
            'shortDescription' => 'required|string',
           // 'status' => 'required|string',
            'authorId' => 'required',
            'approverNotes' => 'required',
            'sellPrice' => 'required',
          
            'discount' => 'required',
            'partnerPrice' => 'required',
            'productId' => 'required|int|exists:products,id',
            'partnerId' => 'required|exists:companies,id',
            'delimitation' => 'required',
            'serviceDescription' => 'required',
            'serviceHours' => 'required',
            //'scripts' => 'required|array',
          /*   'products.*' => 'int|exists:products,id',
            'productsImages.*.base64' => 'required',
            'productsImages.*.size' => 'required',
            'productsImages.*.name' => 'required', */
        ];

    /*     if ($this->method() == 'PUT') {
            $rules['id'] = 'exists:product_store_requests,id';
             $rules['itemNumber'] = [
                'required',
                Rule::unique('product_store_requests', 'item_number')->ignore($this->route('product-store-request'))
                ]; 
        } */
        return $rules;
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('product_store_requests');
        return $data;
    }
}