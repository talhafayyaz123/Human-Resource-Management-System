<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\ContractAttachment;
use App\Models\InvoiceTemplate;
use App\Models\InvoiceTemplateProduct;
use App\Models\SelectedContractField;
use App\Models\ContractAttachmentProduct;
use App\Models\ContractFieldProduct;
use App\Models\OutboundedContract;
use App\Models\Invoice;
use Carbon\Carbon;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\DB;
use App\Traits\CustomHelper;
use Error;

class OutboundedContractService
{
    use CustomHelper;

    /**
     * Create a new fleet car.
     *
     * @param array $data
     * @return \App\Models\FleetCar
     */
    public function createOutboundedContract(array $data, $userId)
    {
        $outbounded_contract = OutboundedContract::create([
            ...$data,
        ]);
        // sync attachments and contract fields
        $this->syncAttachmentsAndContractFields($data, $outbounded_contract);
//        $this->createInvoiceTemplate($outbounded_contract, $userId);
        return $outbounded_contract;
    }

    /**
     * Update the specified fleet car.
     *
     * @param \App\Models\FleetCar $fleetCar
     * @param array $data
     * @return \App\Models\FleetCar
     */
    public function updateOutboundedContract(OutboundedContract $outbounded_contract, array $data, $userId)
    {
        $outbounded_contract->update([
            ...$data,
        ]);
        // sync attachments and contract fields
        $this->syncAttachmentsAndContractFields($data, $outbounded_contract);
//        $this->createInvoiceTemplate($outbounded_contract, $userId);
        return $outbounded_contract;
    }

    /**
     * Delete the specified fleet car.
     *
     * @param \App\Models\FleetCar $fleetCar
     * @return void
     */
    public function deleteOutboundedContract(OutboundedContract $outbounded_contract)
    {
        $outbounded_contract->delete();
    }

    /**
     * creates invoice based on outbounded contract attachments
     * @param OutboundedContract $outbounded_contract
     * @param Request $request
     */
    public function createInvoice(OutboundedContract $outbounded_contract, $request)
    {
        $sla_attachment_ids = $outbounded_contract->attachments()->where('allow_to_add_slas', 1)->pluck('contract_attachment.id');
        $sla_attachment_products = ContractAttachmentProduct::whereIn('contract_attachment_id', $sla_attachment_ids)->get();
        $customer = $outbounded_contract->receiver;
        if (!$customer->defaultPaymentPeriod) {
            throw new Error('Kindy set the default payment period on ' . $customer->company_name . ' to proceed');
        }
        $paymentPeriod = $customer->defaultPaymentPeriod;
        if (!$customer->termsOfPayment) {
            throw new Error('Kindy set the terms of payment on ' . $customer->company_name . ' to proceed');
        }
        $termOfPayment = $customer->termsOfPayment;
        $invoice = new Invoice();
        $invoice->invoice_type = 'invoice';
        $invoice->due_date = Carbon::now()->addDays($termOfPayment?->no_of_days_1 ?? 0);
        $invoice->start_date = Carbon::now();
        $invoice->end_date = Carbon::now()->endOfYear();
        $invoice->invoice_period = $paymentPeriod->id;
        $invoice->terms_of_payment = $termOfPayment->id;
        $invoice->invoice_category = 'ams';
        $invoice->user_id = $this->getAuthUserId($request);
        $invoice->company_id = $customer->id;
        $invoice->grouped_by = 'nothing';
        $invoice->payment_terms = $termOfPayment->invoice_text;
        $invoice->apply_reverse_change = $customer->apply_reverse_change;
        $invoice->external_order_number = null;
        $invoice->recreate_after = null;
        $invoice->draft_status_change_date = Carbon::now();
        $invoice->total_amount = 0;
        $invoice->total_amount_without_tax = 0;
        $invoice->total_tax_amount = 0;
        $invoice->save();

        $total_tax_amount = 0;
        $netto_total = 0;
        $sum = 0;

        foreach ($sla_attachment_products as $product) {
            $total_tax_amount += $invoice->apply_reverse_change ? 0 : (($product->total_netto * $product->tax) / 100);
            $netto_total += $product->total_netto;
            $sum += $total_tax_amount;
            $invoice->products()->attach($product->product_id, [
                'sale_price' => $product->sale_price ?? 0, 'discount' => $product->discount,
                'quantity' => $product->quantity, 'total_price' => $product->total_amount ?? 0,
                'price_without_tax' => $product->total_netto,
                'tax' => $product->tax,
                'hourly_rate' => $product->hourly_rate,
                'price_per_period' => $product->price_per_period,
                'service_hours' => $product->service_hours,
                'maintenance_rate' => $product->maintenance_rate,
                'payment_period' => $product->payment_period,
                'duration' => $product->duration ?? 1,
                'additional_description' => $product->additional_description,
            ]);
        }

        $sum += $netto_total;

        $invoice->total_amount = $sum;
        $invoice->total_amount_without_tax = $netto_total;
        $invoice->total_tax_amount = $total_tax_amount;

        $invoice->save();
    }

    /**
     * syncs the attachments and contract fields to the outbounded_contract model
     * @param array $data
     * @param OutboundedContract $outbounded_contract
     */
    private function syncAttachmentsAndContractFields(array $data, OutboundedContract $outbounded_contract)
    {
        // add contract_number to contract if status is set to other than 'draft'
        if (!$outbounded_contract->contract_number && $data['status'] != 'draft') {
            $global_offer_setting = GlobalSetting::where('key', 'outboundedContract')->where('year', date('Y'))->first();
            if (empty($global_offer_setting)) {
                $global_setting = new GlobalSetting();
                $global_setting->key = 'outboundedContract';
                $global_setting->value = 1;
                $global_setting->year = date('Y');
                $global_setting->save();
            } else {
                $global_setting = tap(DB::table('global_settings')->where('key', 'outboundedContract')->where('year', date('Y')))->update([
                    'value' => DB::raw('value+1')
                ])->first();
            }
            $outbounded_contract->contract_number = 'RV-' . date('Ym') . '-' . $global_setting->value;
        }
        if (isset($data['attachments'])) {
            SelectedContractField::where('contract_id', $outbounded_contract->id)->delete();
            $savedContractAttachmentIds = [];
            // map the sla values for syncing
            foreach ($data['attachments'] as $attachment) {
                $contractAttachment = ContractAttachment::firstOrNew(['id' => @$attachment['contractAttachmentId']]);
                $contractAttachment->contract_id = $outbounded_contract->id;
                $contractAttachment->attachment_id = $attachment['id'];
                $contractAttachment->sla_infrastructure_id = @$attachment['slaInfrastructureId'];
                $contractAttachment->sla_infrastructure_user_support = @$attachment['slaInfrastructureUserSupport'];
                $contractAttachment->sla_service_time_id = @$attachment['slaServiceTimeId'];
                $contractAttachment->sla_level_change_id = @$attachment['slaLevelChangeId'];
                $contractAttachment->sla_level_incident_id = @$attachment['slaLevelIncidentId'];
                $contractAttachment->sla_level_id = @$attachment['slaLevelId'];
                $contractAttachment->is_fixed_price = @$attachment['isFixedPrice'];
                $contractAttachment->hourly_rate = @$attachment['hourlyRate'];
                $contractAttachment->hours_per_year = @$attachment['hoursPerYear'];
                $contractAttachment->total_price = @$attachment['totalPrice'];
                $contractAttachment->user_id = @$attachment['userId'];
                $contractAttachment->start_date = isset($attachment['startDate']) ? Carbon::parse($attachment['startDate']) : null;
                $contractAttachment->termination_date = isset($attachment['terminationDate']) ? Carbon::parse($attachment['terminationDate']) : null;
                $contractAttachment->signed_date = isset($attachment['signedDate']) ? Carbon::parse($attachment['signedDate']) : null;
                // add attachment_number to contract_attachment if status is set to other than 'draft'
                if ($data['status'] != 'draft') {
                    if (!$contractAttachment->attachment_number) {
                        $global_offer_setting = GlobalSetting::where('key', $contractAttachment->attachment->prefix)->first();
                        if (empty($global_offer_setting)) {
                            $global_setting = new GlobalSetting();
                            $global_setting->key = $contractAttachment->attachment->prefix;
                            $global_setting->value = 1;
                            $global_setting->save();
                        } else {
                            $global_setting = tap(DB::table('global_settings')->where('key', $contractAttachment->attachment->prefix))->update([
                                'value' => DB::raw('value+1')
                            ])->first();
                        }
                        $attachment_number = $contractAttachment->attachment->prefix . date('Ym') . '-' . $global_setting->value;
                        $contractAttachment->attachment_number = $attachment_number;
                    }
                }
                $contractAttachment->save();
                $savedContractAttachmentIds[] = $contractAttachment->id;
                $this->saveContractAttachmentProducts($contractAttachment, $attachment);
                if (isset($attachment['contractFields'])) {
                    // map the relevant contract_fields data based on type
                    foreach ($attachment['contractFields'] as $field) {
                        $selected_contract_field = new SelectedContractField();
                        $selected_contract_field->text = $field['type'] == 'text' ? $field['value'] : null;
                        $selected_contract_field->number = $field['type'] == 'number' ? $field['value'] : null;
                        $selected_contract_field->date = $field['type'] == 'date' ? $field['value'] : null;
                        $selected_contract_field->time = $field['type'] == 'time' ? $field['value'] : null;
                        $selected_contract_field->customer = $field['type'] == 'customer' ? $field['value'] : null;
                        $selected_contract_field->invoice = $field['type'] == 'invoice' ? $field['value'] : null;
                        $selected_contract_field->performance_record = $field['type'] == 'performance-record' ? $field['value'] : null;
                        $selected_contract_field->contract_id = $outbounded_contract->id;
                        $selected_contract_field->contract_field_id = $field['id'];
                        $selected_contract_field->contract_attachment_id = $contractAttachment->id;

                        $attachmentRecord = Attachment::find($attachment['id']);
                        if ($attachmentRecord->add_products_to_customer_licences) {
                            $selected_contract_field->save();
                            if ($field['type'] == 'offer' || $field['type'] == 'offer-confirmation') {
                                $this->saveContractFieldProducts($selected_contract_field, $field);
                                $this->attachLicence($selected_contract_field, $field);
                            }
                            if ($field['type'] == 'products') {
                                $this->saveContractFieldProducts($selected_contract_field, $field);
                                $this->attachLicence($selected_contract_field, $field);
                            }
                        } else {
                            $selected_contract_field->offer = $field['type'] == 'offer' ? $field['value'] : null;
                            $selected_contract_field->offer_confirmation = $field['type'] == 'offer-confirmation' ? $field['value'] : null;
                            $selected_contract_field->save();
                            if ($field['type'] == 'products') {
                                $this->saveContractFieldProducts($selected_contract_field, $field);
                            }
                        }
                    }
                }
            }
            ContractAttachment::where('contract_id', $outbounded_contract->id)
                ->whereNotIn('id', $savedContractAttachmentIds)->delete();
        }

        if ($outbounded_contract->isDirty('contract_number') && $outbounded_contract->status != 'draft') {
            $attachments = $outbounded_contract->attachments()->where('allow_to_add_slas', 1)->get();
            foreach ($attachments as $attachment) {
                $global_invoice_setting = GlobalSetting::where('key', 'ams')->first();
                if (empty($global_invoice_setting)) {
                    $global_setting = new GlobalSetting();
                    $global_setting->key = 'ams';
                    $global_setting->value = 100000;
                    $global_setting->save();
                } else {
                    $global_setting = tap(DB::table('global_settings')->where('key', 'ams'))->update([
                        'value' => DB::raw('value+1')
                    ])->first();
                }
                $value = 'AMS-' . $global_setting->value;
                $attachment->pivot->ams()->create([
                    'attachment_id' => $attachment->pivot->id,
                    'customer_id' => $outbounded_contract->receiver_id,
                    'service_hours_year' => $attachment->pivot?->hours_per_year ?? 0,
                    'remaining_hours' => $attachment->pivot?->hours_per_year ?? 0,
                    'hourly_rate' => $attachment->pivot?->hourly_rate ?? 0,
                    'monthly_amount' => (($attachment->pivot?->hourly_rate ?? 0) * ($attachment->pivot?->hours_per_year ?? 0)) / 12,
                    'ams_number' => $value,
                    'software_id' => $attachment->software_id
                ]);
            }
        }
        $outbounded_contract->save();
    }

    private function saveContractAttachmentProducts($contractAttachment, $requestAttachment): void
    {
        if (isset($requestAttachment['products'])) {
            ContractAttachmentProduct::where('contract_attachment_id', $contractAttachment->id)->delete();
            foreach ($requestAttachment['products'] as $product) {
                $contractAttachmentProduct = new ContractAttachmentProduct();
                $contractAttachmentProduct->contract_attachment_id = $contractAttachment->id;
                $contractAttachmentProduct->product_id = $product['productId'];
                $contractAttachmentProduct->type = $product['type'];
                $contractAttachmentProduct->quantity = $product['quantity'];
                $contractAttachmentProduct->hourly_rate = @$product['hourlyRate'];
                $contractAttachmentProduct->sale_price = @$product['salePrice'];
                $contractAttachmentProduct->maintenance_price = @$product['maintenancePrice'];
                $contractAttachmentProduct->tax = @$product['tax'];
                $contractAttachmentProduct->discount = @$product['discount'];
                $contractAttachmentProduct->total_netto = @$product['totalNetto'];
                $contractAttachmentProduct->total_amount = @$product['totalAmount'];
                $contractAttachmentProduct->payment_period = @$product['paymentPeriod'];
                $contractAttachmentProduct->product_category_id = @$product['productCategoryId'];
                $contractAttachmentProduct->price_per_period = @$product['pricePerPeriod'];
                $contractAttachmentProduct->duration = @$product['duration'];
                $contractAttachmentProduct->additional_description = @$product['additionalDescription'];
                $contractAttachmentProduct->sla_service_time_id = @$product['slaServiceTimeId'];
                $contractAttachmentProduct->sla_level_incident_id = @$product['slaLevelIncidentId'];
                $contractAttachmentProduct->sla_level_id = @$product['slaLevelId'];
                $contractAttachmentProduct->sla_level_change_id = @$product['slaLevelChangeId'];
                $contractAttachmentProduct->service_hours = @$product['serviceHours'];
                $contractAttachmentProduct->product_name = @$product['name'] ?? '';
                $contractAttachmentProduct->save();
            }
        }
    }

    private function saveContractFieldProducts($contractField, $requestContractField): void
    {
        if (isset($requestContractField['value'])) {
            ContractFieldProduct::where('contract_field_id', $contractField->id)->delete();
            foreach ($requestContractField['value'] as $product) {
                $contractFieldProduct = new ContractFieldProduct();
                $contractFieldProduct->contract_field_id = $contractField->id;
                $contractFieldProduct->type = @$product['type'];
                $contractFieldProduct->product_id = isset($product['productId']) ? $product['productId'] : $product['id'];
                $contractFieldProduct->quantity = $product['quantity'];
                $contractFieldProduct->hourly_rate = @$product['hourlyRate'];
                $contractFieldProduct->sale_price = @$product['salePrice'];
                $contractFieldProduct->maintenance_price = @$product['maintenancePrice'];
                $contractFieldProduct->maintenance_rate = @$product['maintenanceRate'];
                $contractFieldProduct->tax = @$product['tax'];
                $contractFieldProduct->discount = @$product['discount'];
                $contractFieldProduct->total_netto = @$product['totalNetto'];
                $contractFieldProduct->total_amount = @$product['totalAmount'];
                $contractFieldProduct->payment_period = @$product['paymentPeriod']['id'];
                $contractFieldProduct->product_category_id = @$product['productCategoryId'];
                $contractFieldProduct->price_per_period = @$product['pricePerPeriod'];
                $contractFieldProduct->duration = @$product['duration'];
                $contractFieldProduct->additional_description = @$product['additionalDescription'];
                $contractFieldProduct->sla_service_time_id = @$product['slaServiceTimeId'];
                $contractFieldProduct->sla_level_incident_id = @$product['slaLevelIncidentId'];
                $contractFieldProduct->sla_level_id = @$product['slaLevelId'];
                $contractFieldProduct->sla_level_change_id = @$product['slaLevelChangeId'];
                $contractFieldProduct->service_hours = @$product['serviceHours'];
                $contractFieldProduct->product_name = @$product['name'] ?? '';
                $contractFieldProduct->save();
            }
        }
    }

    private function attachLicence($selected_contract_field, $field)
    {
        if ($selected_contract_field->contractAttachment->signed_date) {
            if ($field['type'] == 'offer' || $field['type'] == 'offer-confirmation' || $field['type'] == 'product') {
                $products = $selected_contract_field->contractFieldProducts->whereIn('type', ['license', 'cloud', 'software', 'cloud-software']);
                $contractAttachment = $selected_contract_field->contractAttachment;
                $contract = $contractAttachment->outboundedContract;
                foreach ($products as $product) {
                    $contractAttachment->license()->updateOrCreate(
                        [
                            'product_id' => $product->product_id,
                            'contract_attachment_id' => $contractAttachment->id
                        ],
                        [
                            'company_id' => $contract->receiver_id,
                            'quantity' => $product->quantity,
                            'sale_price' => $product->sale_price,
                            'maintenance_price' => $product->maintenance_price
                        ]
                    );
                }
            }
        }
    }

    private function createInvoiceTemplate($contract, $userId)
    {
        if ($contract->receiver->default_payment_period && $contract->receiver->terms_of_payment) {
            foreach ($contract->attachments as $attachment) {
                if ($attachment->add_products_to_customer_licences && $attachment->pivot->signed_date) {
                    InvoiceTemplate::where('contract_attachment_id', $attachment->pivot->id)->delete();
                    $invoiceTemplate = new InvoiceTemplate();
                    $invoiceTemplate->contract_attachment_id = $attachment->pivot->id;
                    $invoiceTemplate->group_by = 'nothing';
                    $invoiceTemplate->invoice_category = 'maintenance';
                    $invoiceTemplate->receiver_type = $contract->receiver_type;
                    $invoiceTemplate->company_id = $contract->receiver_id;
                    $invoiceTemplate->apply_reverse_change = $contract->receiver?->apply_reverse_change;
                    $invoiceTemplate->user_id = $userId;
                    $invoiceTemplate->invoice_period = $contract->receiver?->default_payment_period;
                    $invoiceTemplate->terms_of_payment = $contract->receiver?->terms_of_payment;
                    $invoiceTemplate->save();
                    $amountWithoutTax = 0;
                    foreach ($attachment->pivot->selectedContractFields as $field) {
                        if ($field->contractField->type == 'offer' || $field->contractField->type == 'offer-confirmation') {
                            $products = $field->contractFieldProducts->where('type', 'maintenance');
                            foreach ($products as $product) {
                                $invoiceTemplateProduct = new InvoiceTemplateProduct();
                                $invoiceTemplateProduct->invoice_template_id = $invoiceTemplate->id;
                                $invoiceTemplateProduct->product_id = $product->product_id;
                                $invoiceTemplateProduct->quantity = $product->quantity;
                                $invoiceTemplateProduct->sale_price = $product->sale_price;
                                $invoiceTemplateProduct->price_without_tax = $product->maintenance_price;
                                $invoiceTemplateProduct->maintenance_rate = $product->maintenance_rate;
                                $invoiceTemplateProduct->tax = $product->tax;
                                $invoiceTemplateProduct->additional_description = $product->additional_description;
                                $invoiceTemplateProduct->save();
                                $amountWithoutTax = $amountWithoutTax + $invoiceTemplateProduct->price_without_tax;
                            }
                        }
                    }
                    $invoiceTemplate->total_amount_without_tax = $amountWithoutTax;
                    $invoiceTemplate->save();
                }
            }
        }
    }

    public function createInvoiceTemplateByContract(OutboundedContract $outbounded_contract, $request) {
        $sla_attachment_ids = $outbounded_contract->attachments()->where('allow_to_add_slas', 1)->pluck('contract_attachment.id');
        $sla_attachment_products = ContractAttachmentProduct::whereIn('contract_attachment_id', $sla_attachment_ids)->get();
        $customer = $outbounded_contract->receiver;
        if (!$customer->defaultPaymentPeriod) {
            throw new Error('Kindly set the default payment period on ' . $customer->company_name . ' to proceed');
        }
        $paymentPeriod = $customer->defaultPaymentPeriod;
        if (!$customer->termsOfPayment) {
            throw new Error('Kindly set the terms of payment on ' . $customer->company_name . ' to proceed');
        }
        $termOfPayment = $customer->termsOfPayment;
        $invoiceTemplate = new InvoiceTemplate();
        $invoiceTemplate->group_by = 'nothing';
        $invoiceTemplate->invoice_category = 'ams';
        $invoiceTemplate->receiver_type = $outbounded_contract->receiver_type;
        $invoiceTemplate->company_id = $outbounded_contract->receiver_id;
        $invoiceTemplate->apply_reverse_change = $outbounded_contract->receiver?->apply_reverse_change;
        $invoiceTemplate->user_id = $this->getAuthUserId($request);;
        $invoiceTemplate->invoice_period = $outbounded_contract->receiver?->default_payment_period;
        $invoiceTemplate->terms_of_payment = $outbounded_contract->receiver?->terms_of_payment;
        $invoiceTemplate->payment_terms = $termOfPayment->invoice_text;
        $invoiceTemplate->total_amount = 0;
        $invoiceTemplate->total_amount_without_tax = 0;
        $invoiceTemplate->total_tax_amount = 0;
        $invoiceTemplate->save();

        $total_tax_amount = 0;
        $netto_total = 0;
        $sum = 0;

        foreach ($sla_attachment_products as $product) {
            $total_tax_amount += $invoiceTemplate->apply_reverse_change ? 0 : (($product->total_netto * $product->tax) / 100);
            $netto_total += $product->total_netto;
            $sum += $total_tax_amount;
            $invoiceTemplateProduct = new InvoiceTemplateProduct();
            $invoiceTemplateProduct->invoice_template_id = $invoiceTemplate->id;
            $invoiceTemplateProduct->product_id = $product->product_id;
            $invoiceTemplateProduct->quantity = $product->quantity;
            $invoiceTemplateProduct->sale_price = $product->sale_price;
            $invoiceTemplateProduct->discount = $product->discount;
            $invoiceTemplateProduct->total_price = $product->total_amount ?? 0;
            $invoiceTemplateProduct->price_without_tax = $product->total_netto;
            $invoiceTemplateProduct->maintenance_rate = $product->maintenance_rate;
            $invoiceTemplateProduct->tax = $product->tax;
            $invoiceTemplateProduct->hourly_rate = $product->hourly_rate;
            $invoiceTemplateProduct->price_per_period = $product->price_per_period;
            $invoiceTemplateProduct->service_hours = $product->service_hours;
            $invoiceTemplateProduct->payment_period = $product->payment_period;
            $invoiceTemplateProduct->duration = $product->duration;
            $invoiceTemplateProduct->additional_description = $product->additional_description;
            $invoiceTemplateProduct->save();
        }

        $sum += $netto_total;

        $invoiceTemplate->total_amount = $sum;
        $invoiceTemplate->total_amount_without_tax = $netto_total;
        $invoiceTemplate->total_tax_amount = $total_tax_amount;

        $invoiceTemplate->save();
    }
}
