<?php

namespace App\Services\PartnerManagement;

use App\Models\MyFeed;
use App\Models\PartnerOrder;
use App\Models\PartnerOrderProduct;
use App\Traits\CustomHelper;

class PartnerOrderService
{
    use CustomHelper;

    protected $model;

    public function __construct()
    {
        $this->model = new PartnerOrder();
    }

    public function create($data)
    {
        $model = new $this->model;
        return $this->prepareData($model, $data);
    }

    private function prepareData($model, $data)
    {
        if (isset($data['orderNo']) && $data['orderNo']) {
            $model->order_number = $data['orderNo'];
        }

        if (isset($data['partnerId']) && $data['partnerId']) {
            $model->partner_id = $data['partnerId'];
        }

        if (isset($data['receiverId']) && $data['receiverId']) {
            $model->receiver_id = $data['receiverId'];
        }

        if (isset($data['contactPerson']) && $data['contactPerson']) {
            $model->contact_person = $data['contactPerson'];
        }
        if (isset($data['contactPartnerPerson']) && $data['contactPartnerPerson']) {
            $model->partner_contact_person = $data['contactPartnerPerson'];
        }

        if (isset($data['createdBy']) && $data['createdBy']) {
            $model->created_by = $data['createdBy'];
        }

        if (isset($data['termId']) && $data['termId']) {
            $model->term_id = $data['termId'];
        }

        if (isset($data['paymentTerms']) && $data['paymentTerms']) {
            $model->payment_terms = $data['paymentTerms'];
        }

        if (isset($data['projectId']) && $data['projectId']) {
            $model->project_id = $data['projectId'];
        }

        if (isset($data['expiryDate']) && $data['expiryDate']) {
            $model->expiry_date = $data['expiryDate'];
        }

        if (isset($data['status']) && $data['status']) {
            $model->status = $data['status'];
        }

        if (isset($data['externalOrderNumber']) && $data['externalOrderNumber']) {
            $model->external_order_number = $data['externalOrderNumber'];
        }

        if (isset($data['identifier']) && $data['identifier']) {
            $model->identifier = $data['identifier'];
        }

        if (isset($data['nettoTotal']) && $data['nettoTotal']) {
            $model->netto_total = $data['nettoTotal'];
        }

        $model->save();
        return $model;
    }

    public function update($data, $id)
    {
        $model = $this->model->where('id', $id)->first();
        return $this->prepareData($model, $data);
    }

    public function saveProducts($products, $order): void
    {
        $partnerOrderProductIds = [];
        foreach ($products as $product) {
            $partnerOrderProduct = PartnerOrderProduct::firstOrNew(['id' => isset($product['partnerOrderProductId']) ? $product['partnerOrderProductId'] : null]);
            $partnerOrderProduct->partner_order_id = $order->id;
            $partnerOrderProduct->product_id = $product['productId'];
            $partnerOrderProduct->quantity = $product['quantity'];
            $partnerOrderProduct->type = $product['componentType'];
            $partnerOrderProduct->hourly_rate = @$product['hourlyRate'];
            $partnerOrderProduct->sale_price = @$product['salePrice'];
            $partnerOrderProduct->maintenance_rate = @$product['maintenanceRate'];
            $partnerOrderProduct->tax = @$product['tax'];
            $partnerOrderProduct->discount = @$product['discount'];
            $partnerOrderProduct->partner_price = @$product['partnerPrice'];
            $partnerOrderProduct->total_netto = @$product['totalNetto'];
            $partnerOrderProduct->total_amount = @$product['totalAmount'];
            $partnerOrderProduct->payment_period = @$product['paymentPeriod'];
            $partnerOrderProduct->price_per_period = @$product['pricePerPeriod'];
            $partnerOrderProduct->duration = @$product['duration'];
            $partnerOrderProduct->additional_description = @$product['additionalDescription'];
            $partnerOrderProduct->product_name = @$product['productName'];
            $partnerOrderProduct->save();
            $partnerOrderProductIds[] = $partnerOrderProduct->id;
        }
        $order->orderProducts()->whereNotIn('id', $partnerOrderProductIds)->delete();
    }
}
