<?php

namespace App\Http\Requests;

use App\Models\GlobalSetting;
use App\Traits\CustomHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class PartnerOrderRequest extends FormRequest
{
    use CustomHelper;

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
        $data = [
            'partnerId' => 'required|exists:companies,id',
            'receiverId' => 'required|exists:companies,id',
            'contactPerson' => 'nullable|string',
            'createdBy' => 'required|string',
            'termId' => 'required|exists:terms_of_payments,id',
            'paymentTerms' => 'nullable|string',
            'projectId' => 'nullable|exists:projects,id',
            'expiryDate' => 'required|date',
            'status' => 'required',
            'externalOrderNumber' => 'nullable',
            'identifier' => 'nullable',
            'nettoTotal' => 'nullable|numeric',
            'products' => 'nullable|array',
            'products.*.productId' => 'required|exists:products,id',
            'products.*.componentType' => 'required|in:partner_price_list,own_price_list',
            'products.*.quantity' => 'required|numeric',
        ];

        if ($this->method() == 'PUT') {
            $data['id'] = 'exists:partner_orders,id';
        }
        return $data;
    }

    public function prepareRequest()
    {
        $request = $this;
        $data = [
            'partnerId' => $request['partnerId'],
            'receiverId' => $request['receiverId'],
            'contactPerson' => $request['contactPerson'],
            'contactPartnerPerson' => $request['contactPartnerPerson'],
            'createdBy' => $request['createdBy'],
            'termId' => $request['termId'],
            'paymentTerms' => $request['paymentTerms'],
            'projectId' => $request['projectId'],
            'expiryDate' => $request['expiryDate'],
            'status' => $request['status'],
            'externalOrderNumber' => $request['externalOrderNumber'],
            'identifier' => $request['identifier'],
            'nettoTotal' => $request['nettoTotal'],
        ];

        if ($this->method() == 'POST') {
            $global_offer_setting = GlobalSetting::where('key', 'partner_order')->first();
            if (empty($global_offer_setting)) {
                $global_setting = new GlobalSetting();
                $global_setting->key = 'partner_order';
                $global_setting->value = 450001;
                $global_setting->save();
            } else {
                $global_setting = tap(DB::table('global_settings')->where('key', 'partner_order'))->update([
                    'value' => DB::raw('value+1')
                ])->first();
            }
            $data['orderNo'] = $global_setting->value;
        }

        return $data;
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('order');
        return $data;
    }

}
