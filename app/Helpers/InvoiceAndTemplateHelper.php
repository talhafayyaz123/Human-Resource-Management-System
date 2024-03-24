<?php

namespace App\Helpers;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\Company;
use App\Models\GlobalSetting;
use App\Models\Invoice;
use App\Models\PaymentPeriod;
use App\Models\PerformanceRecord;
use App\Models\Product;
use App\Models\ProductInvoiceCategory;
use App\Models\System;
use App\Traits\CustomHelper;
use App\Utils\Logger;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as staticRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class InvoiceAndTemplateHelper
{
    use CustomHelper;

    public static function index($request, $isInvoiceTemplate = 0)
    {
        $per_page = !empty($request->perPage) ? $request->perPage : 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new Invoice;
        if ($sort_by && $sort_order) {
            $model = self::applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        if ($request->forCustomerPortal) {
            $model = $model->whereNotIn('status', ['draft', 'approved']);
        }

        $invoice = $model->orderBy('invoices.created_at')
            ->where('is_invoice_template', $isInvoiceTemplate)
            ->when($request->customerId, function ($query) use ($request) {
                $query->where('company_id', $request->customerId ?? null);
            })
            ->filter(staticRequest::only('search', 'type', 'status'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoiceType' => $invoice->invoice_type,
                    'invoiceNumber' => $invoice->invoice_number,
                    'company' => $invoice->company->company_name,
                    'notes' => $invoice->custom_notes_field,
                    'invoicePeriod' => $invoice->paymentPeriod?->billing_cycle,
                    "status" => $invoice->status,
                    "netto" => $invoice->total_amount_without_tax ?? 0,
                    'dueDate' => Carbon::parse($invoice->due_date)->toDateString(),
                    'createdAt' => Carbon::parse($invoice->created_at)->toDateString(),
                    'updatedAt' => Carbon::parse($invoice->created_at)->toDateString(),
                    'performanceRecordId' => $invoice->performanceRecord?->id,
                    'invoiceEmail' => $invoice->company?->invoice_email ?? "",
                    'category' => $invoice->invoice_category,
                    'startDate' => Carbon::parse($invoice->start_date)->toDateString(),
                    'endDate' => Carbon::parse($invoice->end_date)->toDateString(),
                    'userId' => $invoice->user_id,
                    'userName' => $invoice->user?->full_name,
                    'recreateAfter' => $invoice->recreate_after,
                    'termOfPayment' => $invoice->termsOfPayment ? [
                        'id' => $invoice->termsOfPayment->id,
                        'name' => $invoice->termsOfPayment->name,
                        'paymentTerms' => $invoice->termsOfPayment->payment_terms,
                    ] : null,
                    'nextCreateDate' => $invoice->next_create_date ? Carbon::parse($invoice->next_create_date)->toDateString() : null,
                    'draftStatusChangeDate' => Carbon::parse($invoice->draft_status_change_date)->toDateString(),
                    'externalOrderNumber' => $invoice->external_order_number,
                    'companyDetails' => $invoice->company ? [
                        'id' => $invoice->company?->id,
                        'companyName' => $invoice->company?->company_name,
                        'vatId' => $invoice->company?->vat_id,
                        'url' => $invoice->company?->url,
                        'type' => $invoice->company?->type,
                        'customerType' => $invoice->company?->customer_type,
                        'companyNumber' => $invoice->company?->company_number,
                        'state' => $invoice->company?->headQuarters?->state,
                        'city' => $invoice->company?->headQuarters?->city,
                        'country' => $invoice->company?->headQuarters?->country,
                        'address' => $invoice->company?->headQuarters?->address_first,
                        'code' => $invoice->company?->headQuarters?->zip,
                        'notes' => $invoice->company?->notes,
                        'status' => $invoice->company?->status,
                        'mergePdfsOnDefault' => $invoice->company?->merge_pdfs_on_default,
                        'expiryDate' => $invoice->company?->expiry_dt ? Carbon::parse($invoice->company?->expiry_dt)->toDateString() : '',
                        'termsOfPayment' => $invoice->company?->terms_of_payment,
                    ] : null,
                    'performanceRecordDetails' => $invoice->performanceRecord ? [
                        'id' => $invoice->performanceRecord->id,
                        'company' => $invoice->performanceRecord->customer?->company_name,
                        'companyId' => $invoice->performanceRecord->customer?->id,
                        'advisorId' => $invoice->performanceRecord->advisor_id,
                        'companyNumber' => $invoice->performanceRecord->customer?->company_number,
                        'defaultServiceProduct' => $invoice->performanceRecord->customer?->default_service_product ?? null,
                        'defaultServiceHourlyRate' => $invoice->performanceRecord->customer?->default_service_hourly_rate ?? null,
                        'defaultServiceDailyRate' => $invoice->performanceRecord->customer?->default_service_daily_rate ?? null,
                        'invoice' => $invoice->performanceRecord?->invoice,
                        'totalHours' => $invoice->performanceRecord->total_hours,
                        'goodWillHours' => $invoice->performanceRecord->good_will_hours,
                        'startDate' => $invoice->performanceRecord->start_date,
                        'endDate' => $invoice->performanceRecord->end_date,
                        'createdDate' => Carbon::parse($invoice->performanceRecord->created_at)->format('Y-m-d'),
                        'performanceNumber' => $invoice->performanceRecord->performance_number,
                        'status' => $invoice->performanceRecord->status,
                        'updatedAt' => Carbon::parse($invoice->performanceRecord->updated_at)->toDateString(),
                        'editedUserId' => $invoice->performanceRecord->edited_user_id,
                    ] : null
                ];
            });
        return response()->json(['invoices' => $invoice, 'invoiceTypes' => InvoiceType::ALL], 200);
    }

    public static function referenceInvoices($id = '', $isInvoiceTemplate = 0)
    {
        $model = new Invoice;
        if ($id) {
            $model = $model->where('id', '!=', $id);
        }
        $invoice = $model->select('id', 'invoice_number')->orderBy('invoices.created_at')
            ->where('is_invoice_template', $isInvoiceTemplate)
            ->get('id');
        return response()->json(['invoices' => $invoice, 'invoiceTypes' => InvoiceType::ALL], 200);
    }

    public static function store($request, $isInvoiceTemplate = 0)
    {

        $invoice_id = null;
        $invoice = new Invoice;

        try {
            DB::transaction(function () use ($request, $invoice, $isInvoiceTemplate) {
                $invoice->invoice_type = $request->invoiceType;
                $invoice->receiver_type = $request->receiverType;
                $invoice->due_date = Carbon::parse($request->dueDate);
                $invoice->start_date = $request->startDate ? Carbon::parse($request->startDate) : null;
                $invoice->end_date = $request->endDate ? Carbon::parse($request->endDate) : null;
                $invoice->invoice_period = isset($request->invoicePeriod['id']) ? $request->invoicePeriod['id'] : null;
                $invoice->terms_of_payment = $request->termsOfPayment;
                $invoice->custom_notes_field = $request->customNotesFields;
                $invoice->total_amount = $request->totalAmount;
                $invoice->total_amount_without_tax = $request->totalAmountWithoutTax;
                $invoice->total_tax_amount = $request->totalTaxAmount;
                $invoice->invoice_category = $request->category;
                $invoice->user_id = $request->userId;
                $invoice->company_id = $request->customers['id'];
                $invoice->system_id = isset($request->systems['id']) ? $request->systems['id'] : null;
                $invoice->project_id = $request->projectId;
                $invoice->grouped_by = $request->groupedBy;
                $invoice->payment_terms = $request->paymentTerms;
                $invoice->contact_person = $request->contactPerson;
                $invoice->apply_reverse_change = $request->applyReverseChange;
                $invoice->external_order_number = $request->externalOrderNumber ?? null;
                $invoice->recreate_after = $request->recreateAfter ?? null;
                $invoice->is_invoice_template = $isInvoiceTemplate;
                $invoice->next_create_date = @$request->nextCreateDate;

                if ($request->draftStatusChangeDate) {
                    $invoice->draft_status_change_date = Carbon::parse($request->draftStatusChangeDate);
                }

                if (isset($request->isInvoiceCancel) && $request->isInvoiceCancel == 1 && $invoice->invoice_type == "invoice-storno") {
                    $invoice->reference_invoice = @$request->invoiceId;
                }

                $invoice->save();
                if ($invoice->invoice_category == "service" && isset($request->performanceRecord)) {
                    $performance_record = PerformanceRecord::findOrFail($request->performanceRecord);
                    $performance_record->invoice_id = $invoice->id;
                    $performance_record->status = "approved";
                    $performance_record->save();
                    $invoice->performance_record_id = $performance_record->id;
                    $invoice->save();
                }
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
        $invoice_id = $invoice->id;

        if ($request->category == 'service' && $request->groupedBy == 'category') {
            $products = collect($request->products);
            $categories = $request->categories;
            foreach ($categories as $category) {
                $categoryProduct = $products->where('category.id', $category["id"])->pluck('id')->toArray();
                $categoryProductNames = $products->where('category.id', $category["id"])->pluck('name')->toArray();

                $data = [
                    'invoice_id' => $invoice_id,
                    'product_ids' => $categoryProduct,
                    'category_id' => isset($category["id"]) ? $category["id"] : null,
                    'product_names' => isset($categoryProductNames) ? $categoryProductNames : null,
                    'quantity' => isset($category["quantity"]) ? $category["quantity"] : null,
                    'hourly_rate' => isset($category["hourlyRate"]) ? $category["hourlyRate"] : null,
                    'discount' => isset($category["discount"]) ? $category["discount"] : null,
                    'tax' => isset($category["tax"]) ? $category["tax"] : null,
                    'tax_rate' => isset($category["taxRate"]) ? $category["taxRate"] : null,
                    'netto_total' => isset($category["nettoTotal"]) ? $category["nettoTotal"] : null,
                    'additional_description' => isset($category["additionalDescription"]) ? $category["additionalDescription"] : null,
                ];
                ProductInvoiceCategory::create($data);
            }
        } else {

            foreach ($request->products as $product) {
                // attach the products to the invoice
                $invoice->products()->attach($product['id'], [
                    'sale_price' => $product['salePrice'] ?? 0, 'discount' => $product['discount'],
                    'quantity' => $product['quantity'], 'total_price' => $product['totalPrice'],
                    'price_without_tax' => $product['priceWithoutTax'],
                    'tax' => $product['tax'],
                    'hourly_rate' => isset($product['hourlyRate']) ? $product['hourlyRate'] : null,
                    'product_name' => isset($product['name']) ? $product['name'] : null,
                    'price_per_period' => isset($product['pricePerPeriod']) ? $product['pricePerPeriod'] : null,
                    'service_hours' => isset($product['serviceHours']) ? $product['serviceHours'] : null,
                    'maintenance_rate' => isset($product['maintenanceRate']) ? $product['maintenanceRate'] : null,
                    'payment_period' => isset($product['paymentPeriod']) ? (isset($product['paymentPeriod']["id"]) ? $product['paymentPeriod']["id"] : $product['paymentPeriod']) : null,
                    'duration' => isset($product['duration']) ? $product['duration'] : 1,
                    'additional_description' => isset($product["additionalDescription"]) ? $product["additionalDescription"] : null,
                ]);
                // fetch the created product_invoice
                $product_invoice = $invoice->products()->latest()->first();
                if (isset($product['children'])) {
                    // attach the children
                    foreach ($product['children'] as $child) {
                        $invoice->products()->attach($child['id'], [
                            'sale_price' => $child['salePrice'] ?? 0, 'discount' => $child['discount'],
                            'quantity' => $child['quantity'], 'total_price' => isset($child['totalPrice']) ? $child['totalPrice'] : $child['totalRate'],
                            'price_without_tax' => isset($child['nettoTotal']) ? $child['nettoTotal'] : 0,
                            'tax' => $child['tax'],
                            'product_name' => isset($child['name']) ? $child['name'] : null,
                            'hourly_rate' => isset($child['hourlyRate']) ? $child['hourlyRate'] : null,
                            'price_per_period' => isset($child['pricePerPeriod']) ? $child['pricePerPeriod'] : null,
                            'service_hours' => isset($child['serviceHours']) ? $child['serviceHours'] : null,
                            'maintenance_rate' => isset($child['maintenanceRate']) ? $child['maintenanceRate'] : null,
                            'payment_period' => isset($child['paymentPeriod']) ? (isset($child['paymentPeriod']["id"]) ? $child['paymentPeriod']["id"] : $child['paymentPeriod']) : null,
                            'duration' => isset($child['duration']) ? $child['duration'] : 1,
                            'additional_description' => isset($child["additionalDescription"]) ? $child["additionalDescription"] : null,
                            'parent_product_invoice_id' => $product_invoice->pivot->id
                        ]);
                    }
                }
            }
        }
        return response()->json(['message' => 'Record successfully created.', "id" => $invoice_id], 200);
    }


    public static function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        $data = [
            'pInvoices' => [
                'id' => $invoice->id,
                'invoiceNumber' => $invoice->invoice_number,
                'receiverType' => $invoice->receiver_type,
                'invoiceType' => $invoice->invoice_type,
                'referenceInvoice' => $invoice->reference_invoice,
                'referenceInvoiceDetail' => $invoice->referenceInvoice ? [
                    'id' => $invoice->referenceInvoice?->id ?? null,
                    'invoiceNumber' => $invoice->referenceInvoice?->invoice_number ?? null,
                    'dueDate' => Carbon::parse($invoice->referenceInvoice?->due_date)->format('Y-m-d'),
                    'startDate' => $invoice->referenceInvoice?->start_date ? Carbon::parse($invoice->referenceInvoice?->start_date)->format('Y-m-d') : null,
                    'endDate' => $invoice->referenceInvoice?->end_date ? Carbon::parse($invoice->referenceInvoice?->end_date)->format('Y-m-d') : null,
                ] : null,
                'dueDate' => Carbon::parse($invoice->due_date)->format('Y-m-d'),
                'startDate' => $invoice->start_date ? Carbon::parse($invoice->start_date)->format('Y-m-d') : null,
                'endDate' => $invoice->end_date ? Carbon::parse($invoice->end_date)->format('Y-m-d') : null,
                'invoicePeriod' => $invoice->invoice_period,
                'invoicePeriodName' => $invoice->paymentPeriod ? $invoice->paymentPeriod->name : "-",
                'termsOfPayment' => $invoice->terms_of_payment,
                'externalOrderNumber' => $invoice->external_order_number,
                'termsOfPaymentName' => $invoice->termsOfPayment ? $invoice->termsOfPayment->name . " | " .
                    $invoice->termsOfPayment->payment_terms : "-",
                'termOfPaymentName' => $invoice->termsOfPayment?->name,
                'notes' => $invoice->custom_notes_field,
                'project' => $invoice?->project ?? null,
                'reminderLevelId' => $invoice?->reminder_level_id ?? null,
                'reminderStopUntil' => $invoice?->reminder_stop_until ?? null,
                'reminderStop' => $invoice?->reminder_stop ?? null,
                'groupedBy' => $invoice->grouped_by ?? null,
                'paymentTerms' => $invoice->payment_terms ?? null,
                'performanceRecord' => $invoice->performance_record_id,
                'performanceRecordObject' => $invoice->performanceRecord,
                'totalAmountWithoutTax' => $invoice->total_amount_without_tax,
                'totalTaxAmount' => $invoice->total_tax_amount,
                'totalAmount' => $invoice->total_amount,
                'createdAt' => Carbon::parse($invoice->created_at)->toDateString(),
                'contactPerson' => $invoice->contact_person,
                'applyReverseChange' => $invoice->apply_reverse_change ?? 0,
                'recreateAfter' => $invoice->recreate_after,
                'nextCreateDate' => $invoice->next_create_date ? Carbon::parse($invoice->next_create_date)->toDateString() : null,
                'isInvoiceTemplate' => $invoice->is_invoice_template,
                'draftStatusChangeDate' => isset($invoice->draft_status_change_date) ? Carbon::parse($invoice->draft_status_change_date)->format('Y-m-d') : '',
                'products' => $invoice->products()->whereNull('parent_product_invoice_id')->get()->map(function ($product) {
                    $payment_period = PaymentPeriod::find($product->pivot->payment_period);
                    return [
                        'id' => $product->id,
                        'productInvoiceId' => $product->pivot->id, //productInvoiceId is being used for additional description toggling purposes since it is unique
                        'name' => $product->pivot->product_name ??  $product->name,
                        'isProductNameEdit' => $product->is_product_name_edit,
                        'description' => $product->description,
                        'articleNumber' => $product->article_number,
                        'salePrice' => floatval($product->pivot->sale_price ?? 0) ?? 0,
                        'discount' => floatval($product->pivot->discount),
                        'additionalDescription' => $product->pivot->additional_description,
                        'status' => $product->status ? 'active' : 'deactive',
                        'listingPrice' => $product->listing_price,
                        'profit' => $product->profit,
                        'quantity' => floatval($product->pivot->quantity),
                        'totalPrice' => floatval($product->pivot->total_price),
                        'tax' => +$product->pivot->tax,
                        'priceWithoutTax' => floatval($product->pivot->price_without_tax),
                        'maintenancePrice' => $product->maintanence_price,
                        'maintenanceRate' => floatval($product->pivot->maintenance_rate),
                        'productSoftware' => $product->productSoftware,
                        'unit' => $product->productUnit ? [
                            'id' => $product->productUnit->id,
                            'name' => $product->productUnit->name,
                            'shortName' => $product->productUnit->short_name,
                        ] : null,
                        'type' => $product->payment_details_type,
                        'dailyRate' => $product->daily_rate,
                        'serviceDays' => $product->service_days,
                        'serviceHours' => $product->pivot->service_hours,
                        'hourlyRate' => $product->pivot->hourly_rate,
                        'pricePerPeriod' => $product->pivot->price_per_period,
                        'paymentPeriod' => $payment_period ? [
                            'id' => $payment_period->id,
                            'name' => $payment_period->name,
                            'billingCycle' => $payment_period->billing_cycle,
                            'createdAt' => Carbon::parse($payment_period->created_at)->format('d/m/y')
                        ] : null,
                        'duration' => $product->pivot->duration,
                        'category' => [
                            "id" => $product?->productCategory?->id,
                            "name" => $product?->productCategory?->name,
                            "defaultUnit" => $product?->productCategory?->default_unit,
                            "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                        ],
                        'children' => $product->pivot->children?->map(function ($child) {
                            $child_product = Product::find($child->product_id);
                            return [
                                'id' => $child_product?->id,
                                'productInvoiceId' => $child?->id, //productInvoiceId is being used for additional description toggling purposes since it is unique
                                'name' => $child->product_name ?? $child_product?->name,
                                'isProductNameEdit' => $child_product->is_product_name_edit,
                                'description' => $child_product?->description,
                                'articleNumber' => $child_product?->article_number,
                                'salePrice' => floatval($child->sale_price ?? 0),
                                'discount' => floatval($child?->discount),
                                'additionalDescription' => $child?->additional_description,
                                'status' => $child_product?->status ? 'active' : 'deactive',
                                'listingPrice' => $child_product?->listing_price,
                                'profit' => $child_product?->profit,
                                'quantity' => floatval($child?->quantity),
                                'totalPrice' => floatval($child?->total_price),
                                'tax' => +$child?->tax,
                                'priceWithoutTax' => floatval($child?->price_without_tax),
                                'maintenancePrice' => $child_product?->maintanence_price,
                                'maintenanceRate' => floatval($child?->maintenance_rate),
                                'productSoftware' => $child_product?->productSoftware,
                                'unit' => $child_product?->productUnit ? [
                                    'id' => $child_product?->productUnit->id,
                                    'name' => $child_product?->productUnit->name,
                                    'shortName' => $child_product?->productUnit->short_name,
                                ] : null,
                                'type' => $child_product?->payment_details_type,
                                'dailyRate' => $child_product?->daily_rate,
                                'serviceDays' => $child_product?->service_days,
                                'serviceHours' => $child?->service_hours,
                                'hourlyRate' => $child?->hourly_rate,
                                'pricePerPeriod' => $child?->price_per_period,
                                'paymentPeriod' => $child_product?->paymentPeriod ? [
                                    'id' => $child_product->paymentPeriod->id,
                                    'name' => $child_product->paymentPeriod->name,
                                    'billingCycle' => $child_product->paymentPeriod->billing_cycle,
                                    'createdAt' => Carbon::parse($child_product->paymentPeriod->created_at)->format('d/m/y')
                                ] : null,
                                'duration' => $child?->duration,
                                'category' => [
                                    "id" => $child_product?->productCategory?->id,
                                    "name" => $child_product?->productCategory?->name,
                                    "defaultUnit" => $child_product?->productCategory?->default_unit,
                                    "isDefaultOnOffers" => $child_product?->productCategory?->is_default_on_offers
                                ],
                                'productGroup' => $child_product?->productGroup?->name,
                            ];
                        })
                    ];
                }),
                'customer' => [
                    'id' => $invoice->company?->id,
                    'companyName' => $invoice->company?->company_name,
                    'vatId' => $invoice->company?->vat_id,
                    'url' => $invoice->company?->url,
                    'type' => $invoice->company?->type,
                    'customerType' => $invoice->company?->customer_type,
                    'companyNumber' => $invoice->company?->company_number,
                    'state' => $invoice->company?->headQuarters?->state,
                    'city' => $invoice->company?->headQuarters?->city,
                    'country' => $invoice->company?->headQuarters?->country,
                    'relatedCountry' => $invoice->company?->headQuarters?->relatedCountry?->name,
                    'address' => $invoice->company?->headQuarters?->address_first,
                    'code' => $invoice->company?->headQuarters?->zip,
                    'notes' => $invoice->company?->notes,
                    'status' => $invoice->company?->status,
                    'expiryDate' => $invoice->company?->expiry_dt ? Carbon::parse($invoice->company?->expiry_dt)->toDateString() : '',
                    'termsOfPayment' => $invoice->company?->terms_of_payment,
                    'mergePdfsOnDefault' => $invoice->company?->merge_pdfs_on_default,
                    'externalOrderNumber' => $invoice->company?->external_order_number

                ],
                'systems' => $invoice?->systems ? [
                    'id' => $invoice?->systems?->id,
                    'serverIp' => $invoice?->systems?->server_ip,
                    'type' => $invoice?->systems?->type,
                    'serverPort' => $invoice?->systems?->server_port,
                    'username' => $invoice?->systems?->username,
                    'systemNumber' => $invoice?->systems?->system_number
                ] : null,
                'customer_city' => $invoice->company->headQuarters?->city,
                'customer_country' => $invoice->company->headQuarters?->country,
                'customer_code' => $invoice->company->headQuarters?->zip,
                'customer_address' => $invoice->company->headQuarters?->address_first,
                'invoiceStatus' => $invoice->status,
                'category' => $invoice->invoice_category,
                'userId' => $invoice->user_id,
                'invoiceStatus' => $invoice->status,
            ],
            'products' => Product::whereIn("payment_details_type", ["software", "pauschal"])->orderBy('article_number')
                ->paginate(25)
                ->through(fn ($product) => [
                    'id' => $product->id,
                    'articleNumber' => $product->article_number,
                    'name' => $product->name,
                    'listingPrice' => $product->listing_price,
                    'status' => $product->status ? 'active' : 'deactive',
                    'salePrice' => $product->sale_price,
                    'profit' => $product->profit,
                    'discount' => $product->discount,
                    'quantity' => 1,
                    'tax' => $product->tax,
                    'maintenanceRate' => $product->maintenance_rate,
                    'maintenancePrice' => $product->maintanence_price,
                    'productGroup' => $product->productGroup?->name,
                ]),
            'customers' => Company::orderBy('company_name')
                ->where("customer_type", "customer")
                ->get()
                ->map(fn ($company) => [
                    'id' => $company->id,
                    'url' => $company->url,
                    'name' => $company->company_name,
                    'state' => $company->headQuarters?->state,
                    'city' => $company->headQuarters?->city,
                    'country' => $company->headQuarters?->country,
                    'address' => $company->headQuarters?->address_first,
                    'code' => $company->headQuarters?->zip,
                    'termsOfPayment' => $company->terms_of_payment,
                ]),
            'systems' => System::where('customer_id', $invoice->company->id)->paginate(25)->through(fn ($system) => [
                'id' => $system->id,
                'serverIp' => $system->server_ip,
                'type' => $system->type,
                'serverPort' => $system->server_port,
                'username' => $system->username,
                'systemNumber' => $system->system_number
            ]),
            'invoiceStatuses' => InvoiceStatus::ALL,
            'invoiceTypes' => InvoiceType::ALL,
        ];

        if ($invoice->invoice_category == 'service' && $invoice->grouped_by == 'category') {
            $data['pInvoices']['categories'] = $invoice->invoiceProductGroupByCategory->map(function ($item) {
                $products = [];
                foreach ($item->product_ids as  $index => $product_id) {
                    $product = Product::findOrFail($product_id);
                    if (!empty($item->product_names)) {
                        $product_name = $item->product_names[$index];
                    } else {
                        $product_name = $product->name;
                    }
                    array_push(
                        $products,
                        [
                            'id' => $product->id,
                            'name' => $product_name,
                            'isProductNameEdit' => $product->is_product_name_edit,
                            'description' => $product->description,
                            'articleNumber' => $product->article_number,
                            'salePrice' => floatval($product->sale_price ?? 0),
                            'discount' => floatval($product->discount),
                            'status' => $product->status ? 'active' : 'deactive',
                            'listingPrice' => $product->listing_price,
                            'profit' => $product->profit,
                            'quantity' => floatval($product->quantity),
                            'totalPrice' => floatval($product->total_price),
                            'tax' => +$product->tax,
                            'priceWithoutTax' => floatval($product->price_without_tax),
                            'maintenancePrice' => $product->maintanence_price,
                            'maintenanceRate' => floatval($product->maintenance_rate),
                            'productSoftware' => $product->productSoftware,
                            'unit' => $product->productUnit ? [
                                'id' => $product->productUnit->id,
                                'name' => $product->productUnit->name,
                                'shortName' => $product->productUnit->short_name,
                            ] : null,
                            'type' => $product->payment_details_type,
                            'dailyRate' => $product->daily_rate,
                            'serviceDays' => $product->service_days,
                            'serviceHours' => $product->service_hours,
                            'hourlyRate' => $product->hourly_rate,
                            'pricePerPeriod' => $product->price_per_period,
                            'paymentPeriod' => $product->payment_period,
                            'duration' => $product->duration,
                            'category' => [
                                "id" => $product?->productCategory?->id,
                                "name" => $product?->productCategory?->name,
                                "defaultUnit" => $product?->productCategory?->default_unit,
                                "isDefaultOnOffers" => $product?->productCategory?->is_default_on_offers
                            ],
                            'productGroup' => $product->productGroup?->name,
                        ]
                    );
                }
                return [
                    'categoryId' => $item->category->id,
                    'categoryName' => $item->category->name,
                    'quantity' => floatval($item->quantity),
                    'hourlyRate' => floatval($item->hourly_rate),
                    'discount' => floatval($item->discount),
                    'tax' => floatval($item->tax),
                    'taxRate' => floatval($item->tax_rate),
                    'nettoTotal' => floatval($item->netto_total),
                    'additionalDescription' => $item->additional_description,
                    'products' => $products
                ];
            });
        }
        return response()->json($data);
    }

    public static function update($request, $id)
    {
        try {

            //    dd($request->products);

            $invoice = null;
            DB::transaction(function () use ($request, $id, &$invoice) {
                $invoice = Invoice::lockForUpdate()->findOrFail($id); //added lockForUpdate to prevent concurrent modification
                if ($invoice->status == InvoiceStatus::DRAFT) {
                    $request->validate([
                        'customers' => ['required'],
                        'invoiceType' => 'required|string',
                        'invoicePeriod' => 'required',
                        'termsOfPayment' => 'required',
                        'notes' => 'nullable|string',
                        'dueDate' => 'required|string',
                        'totalAmount' => 'required',
                        'totalAmountWithoutTax' => 'required',
                        'totalTaxAmount' => 'required',
                        'category' => 'required',
                        "startDate" => "required",
                        "endDate" => "required",
                        "receiverType" => "required|in:customer,partner"
                    ], [
                        'systems' => 'Please enter a system to create an invoice',
                        'products' => 'Please enter products to create an invoice',
                        'customers' => 'Please enter an customer to create an invoice'
                    ]);
                } else {
                    $request->validate([
                        'invoiceStatus' => 'required'
                    ]);
                }
                Logger::logInfo("invoice status", [
                    "request_status" => $request->invoiceStatus,
                    "invoice_status" => $invoice->status
                ]);
                if ($invoice->status != InvoiceStatus::DRAFT) {
                    $invoice->status = $request->invoiceStatus;
                    $invoice->due_date = Carbon::parse($request->dueDate);
                    $invoice->save();
                    return response()->json([
                        'message' => 'Status successfully updated.',
                        'invoiceNumber' => $invoice->invoice_number
                    ], 200);
                }

                $invoice->invoice_type = $request->invoiceType;
                $invoice->receiver_type = $request->receiverType;
                $invoice->due_date = Carbon::parse($request->dueDate);
                $invoice->start_date = $request->startDate ? Carbon::parse($request->startDate) : null;
                $invoice->end_date = $request->endDate ? Carbon::parse($request->endDate) : null;
                $invoice->invoice_period = $request->invoicePeriod['id'];
                $invoice->terms_of_payment = $request->termsOfPayment;
                $invoice->custom_notes_field = $request->customNotesFields;
                $invoice->company_id = $request->customers['id'];
                $invoice->system_id = isset($request->systems['id']) ? $request->systems['id'] : null;
                $invoice->status = $request->invoiceStatus;
                $invoice->contact_person = $request->contactPerson;
                $invoice->apply_reverse_change = $request->applyReverseChange ?? 0;
                $invoice->external_order_number = $request->externalOrderNumber ?? null;
                $invoice->next_create_date = @$request->nextCreateDate;
                /** invoice status changed from draft to something as soon invoice status is changed from draft we store the date on which it was changed */
                if ($request->draftStatusChangeDate && $invoice->status == InvoiceStatus::DRAFT) {
                    $invoice->draft_status_change_date = Carbon::parse($request->draftStatusChangeDate);
                }
                /** */
                $invoice->total_amount = $request->totalAmount;
                $invoice->total_amount_without_tax = $request->totalAmountWithoutTax;
                $invoice->total_tax_amount = $request->totalTaxAmount;
                $invoice->invoice_category = $request->category;
                $invoice->recreate_after = $request->recreateAfter ?? null;
                if (empty($request->userId)) {
                    $user_id = $this->getAuthUserId($request);
                    $invoice->user_id = $user_id; // Shift invoice to admin if the original create user does not exist
                }
                $invoice->project_id = $request->projectId;
                $invoice->grouped_by = $request->groupedBy;
                $invoice->payment_terms = $request->paymentTerms;
                if ($invoice->invoice_category == "service" && isset($request->performanceRecord)) {
                    $performance_record = PerformanceRecord::findOrFail($request->performanceRecord);
                    $performance_record->invoice_id = $invoice->id;
                    $performance_record->status = "approved";
                    $performance_record->save();
                    $invoice->performance_record_id = $performance_record->id;
                    $invoice->save();
                }

                if ($request->category == 'service' && $request->groupedBy == 'category') {
                    $products = collect($request->products);
                    $productInvoiceCategory = ProductInvoiceCategory::where('invoice_id', $invoice->id);

                    $invoiceTempalteProductNames = '';
                    if ($productInvoiceCategory->first()) {
                        $record = $productInvoiceCategory->first();
                        $invoiceTempalteProductNames = $record->product_names;
                        $product_ids = $record->product_ids;
                    }

                    $productInvoiceCategory->delete();


                    $categories = $products->pluck('category')->unique()->toArray();
                    $categories = $request->categories;
                    foreach ($categories as $category) {
                        $categoryProduct = $products->where('category.id', $category["id"])->pluck('id')->toArray();
                        $categoryProductNames = $products->where('category.id', $category["id"])->pluck('name')->toArray();

                        if (empty($categoryProduct)) {
                            $categoryProduct = $product_ids;
                        }

                        if (empty($categoryProductNames)) {
                            $categoryProductNames = $invoiceTempalteProductNames;
                        }

                        $data = [
                            'invoice_id' => $invoice->id,
                            'product_ids' => $categoryProduct,
                            'category_id' => isset($category["id"]) ? $category["id"] : null,
                            'quantity' => isset($category["quantity"]) ? $category["quantity"] : null,
                            'hourly_rate' => isset($category["hourlyRate"]) ? $category["hourlyRate"] : null,
                            'discount' => isset($category["discount"]) ? $category["discount"] : null,
                            'tax' => isset($category["tax"]) ? $category["tax"] : null,
                            'tax_rate' => isset($category["taxRate"]) ? $category["taxRate"] : null,
                            'product_names' => isset($categoryProductNames) ? $categoryProductNames : null,
                            'netto_total' => isset($category["nettoTotal"]) ? $category["nettoTotal"] : null,
                            'additional_description' => isset($category["additionalDescription"]) ? $category["additionalDescription"] : null,
                        ];
                        ProductInvoiceCategory::create($data);
                    }
                } else {
                    $invoice->products()->detach();
                    foreach ($request->products as $product) {
                        // attach the product to the invoice
                        $invoice->products()->attach($product['id'], [
                            'sale_price' => $product['salePrice'] ?? 0, 'discount' => $product['discount'],
                            'quantity' => $product['quantity'], 'total_price' => $product['totalPrice'],
                            'price_without_tax' => isset($product['nettoTotal']) ? $product['nettoTotal'] : $product['priceWithoutTax'],
                            'tax' => $product['tax'],
                            'hourly_rate' => isset($product['hourlyRate']) ? $product['hourlyRate'] : null,
                            'product_name' => isset($product['name']) ? $product['name'] : null,
                            'price_per_period' => isset($product['pricePerPeriod']) ? $product['pricePerPeriod'] : null,
                            'service_hours' => isset($product['serviceHours']) ? $product['serviceHours'] : null,
                            'maintenance_rate' => isset($product['maintenanceRate']) ? $product['maintenanceRate'] : null,
                            'payment_period' => isset($product['paymentPeriod']) ? (isset($product['paymentPeriod']["id"]) ? $product['paymentPeriod']["id"] : $product['paymentPeriod']) : null,
                            'duration' => isset($product['duration']) ? $product['duration'] : 1,
                            'additional_description' => isset($product["additionalDescription"]) ? $product["additionalDescription"] : null,
                        ]);
                        // fetch the created product_invoice
                        $product_invoice = $invoice->products()->latest()->first();
                        if (isset($product['children'])) {
                            // save the child products
                            foreach ($product['children'] as $child) {
                                $invoice->products()->attach($child['id'], [
                                    'sale_price' => $child['salePrice'] ?? 0, 'discount' => $child['discount'],
                                    'quantity' => $child['quantity'], 'total_price' => isset($child['totalPrice']) ? $child['totalPrice'] : $child['totalRate'],
                                    'price_without_tax' => isset($child['nettoTotal']) ? $child['nettoTotal'] : '',
                                    'tax' => isset($child['tax']) ? $child['tax'] : '',
                                    'hourly_rate' => isset($child['hourlyRate']) ? $child['hourlyRate'] : null,
                                    'product_name' => isset($child['name']) ? $child['name'] : null,
                                    'price_per_period' => isset($child['pricePerPeriod']) ? $child['pricePerPeriod'] : null,
                                    'service_hours' => isset($child['serviceHours']) ? $child['serviceHours'] : null,
                                    'maintenance_rate' => isset($child['maintenanceRate']) ? $child['maintenanceRate'] : null,
                                    'payment_period' => isset($child['paymentPeriod']) ? (isset($child['paymentPeriod']["id"]) ? $child['paymentPeriod']["id"] : $child['paymentPeriod']) : null,
                                    'duration' => isset($child['duration']) ? $child['duration'] : 1,
                                    'additional_description' => isset($child["additionalDescription"]) ? $child["additionalDescription"] : null,
                                    'parent_product_invoice_id' => $product_invoice->pivot->id
                                ]);
                            }
                        }
                    }
                }

                if (($request->invoiceStatus == InvoiceStatus::APPROVED || $request->invoiceStatus == InvoiceStatus::SENT) &&
                    ($invoice->invoice_category == 'license' || $invoice->invoice_category == 'maintenance' || $invoice->invoice_category == 'cloud-software')
                ) {
                    foreach ($invoice->products as $product) {
                        $license = $product->pivot->license()->create([
                            'company_id' => $invoice->company_id,
                            'product_id' => $product->id,
                            'quantity' => $product->pivot->quantity,
                            'sale_price' => $product->pivot->sale_price,
                            'maintenance_price' => (((float)$product->pivot->sale_price) * (float)$product->pivot->maintenance_rate) / 100,
                        ]);
                        $product->pivot->license()->associate($license);
                        $product->pivot->save();
                    }
                }

                if ($invoice->status != InvoiceStatus::DRAFT && !$invoice->invoice_number) {
                    $global_invoice_setting = GlobalSetting::where('key', 'invoice')->where('year', date("Y"))->first();
                    if (empty($global_invoice_setting)) {
                        $global_setting = new GlobalSetting;
                        $global_setting->key = 'invoice';
                        $global_setting->value = 76001;
                        $global_setting->year = date("Y");
                        $global_setting->save();
                    } else {
                        $global_setting = tap(DB::table('global_settings')->where('key', 'invoice')->where('year', date("Y")))->update([
                            'value' => DB::raw("value+1")
                        ])->first();
                    }
                    Logger::logInfo("invoice number before", [
                        "global_setting_value" => $global_setting->value,
                        "invoice_number" => $invoice->invoice_number,
                    ]);
                    $invoice->invoice_number = Carbon::now()->format('y') . '-' . $global_setting->value;
                    Logger::logInfo("invoice number after", [
                        "global_setting_value" => $global_setting->value,
                        "invoice_number" => $invoice->invoice_number,
                    ]);
                    self::generateInvoicePdf($invoice);
                }
                $invoice->save();
            });
            return response()->json([
                'message' => 'Record successfully updated.',
                'invoiceNumber' => $invoice?->invoice_number,
                "data" => $invoice?->products
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),

            ], 422);
        }
    }

    public static function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $model = Invoice::find($id);
                if ($model->performanceRecord) {
                    $model->performanceRecord->status = 'open';
                    $model->performanceRecord->save();
                }
                $model->delete();
            });
            return response()->json(['message' => 'Record deleted.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace(),
            ], 500);
        }
    }

    public static function applySortingBeforePagination($model, $sortBy, $sortOrder)
    {
        $sortByParts = explode('.', $sortBy);

        if (count($sortByParts) > 1) {
            // Handle sorting with nested relationships
            $nestedColumn = Str::snake(array_pop($sortByParts));
            $nestedRelationship = implode('.', $sortByParts);             //not possible without the use of joins
            $relatedModel = "App\Models\\" . $nestedRelationship;

            $modelInstance = app($relatedModel);
            $foreignKeyName = $model->{$nestedRelationship}()->getForeignKeyName();
            $model = $model::leftJoin($modelInstance->getTable(), "{$model->getTable()}.{$foreignKeyName}", "=", "{$modelInstance->getTable()}.id")
                ->select("{$modelInstance->getTable()}.*", "{$model->getTable()}.*")
                ->orderBy("{$modelInstance->getTable()}.{$nestedColumn}", $sortOrder);
        } else {
            $sortByParts = explode('-', $sortBy);
            if (count($sortByParts) > 1) {

                // Handle sorting with a date range
                $startColumn = Str::snake($sortByParts[0]);
                $endColumn = Str::snake($sortByParts[1]);

                $model = $model->orderBy($startColumn, $sortOrder)
                    ->orderBy($endColumn, $sortOrder);
            } else {
                // Handle sorting with a single column
                $columnName = Str::snake($sortByParts[0]);
                if (strpos($columnName, '_numeric') !== false) {
                    // Remove "_numeric" from the column name for sorting
                    $numericColumnName = str_replace('_numeric', '', $columnName);
                    $model = $model->orderByRaw('CAST(' . $numericColumnName . ' AS SIGNED) ' . $sortOrder);
                } else {
                    $model = $model->orderBy($columnName, $sortOrder);
                }
            }
        }

        return $model;
    }

    private static function generateInvoicePdf(Invoice $invoice): void
    {
        $invoice->load('products');
        $invoice->load('company');
        $data = [
            'invoice' => $invoice,
        ];
        $pdf = Pdf::loadView('invoicePdf', $data);
        $content = $pdf->download()->getOriginalContent();
        $file_name_to_store =
            $invoice->invoice_number . '_' . time() . '.pdf';
        Storage::disk('local')->put('invoices/pdf/' . $file_name_to_store, $content);
    }
}