<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetFormRequest extends FormRequest
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
            'assetName' => 'required|string|max:255',
            'assetType' => 'required|string|max:255',
            'supplierId' => 'required|string',
            'storeArticle' => 'nullable|boolean',
            'priceNetto' => 'required|numeric|min:0',
            'assetImage' => 'nullable',
            'activeAsset' => 'required|boolean',
            'archivedAsset' => 'required|boolean',
            'assetDescription' => 'nullable|string',
            'manufacturer' => 'nullable|string',
        ];
    }
}
