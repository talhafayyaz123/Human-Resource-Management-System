<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceTemplate;
use App\Models\InvoiceTemplateCategoryProduct;
use App\Models\PaymentPeriod;
use App\Models\PerformanceRecord;
use App\Models\Product;
use App\Models\ProductInvoiceCategory;
use App\Models\System;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as staticRequest;

class InvoiceTemplateController extends Controller
{
    use CustomHelper;

    /**
     * Runs when instance is initiated
     */
    public function __construct()
    {
        $this->middleware('check.permission:invoice-templates.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:invoice-templates.create', ['only' => ['store']]);
        $this->middleware('check.permission:invoice-templates.edit', ['only' => ['update']]);
        $this->middleware('check.permission:invoice-templates.delete', ['only' => ['destroy']]);
    }

    /**
     * Display invoice table via pagination.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = !empty($request->perPage) ? $request->perPage : 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new InvoiceTemplate();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        $invoice = $model->orderBy('invoice_templates.created_at')
            ->when($request->customerId, function ($query) use ($request) {
                $query->where('company_id', $request->customerId ?? null);
            })
            ->when($request->category, function ($query) use ($request) {
                $query->where('invoice_category', $request->category ?? null);
            })
            ->filter(staticRequest::only('search', 'type', 'status'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'company' => $invoice->company?->company_name,
                    'notes' => $invoice->custom_notes_fields,
                    'invoicePeriod' => $invoice->paymentPeriod?->billing_cycle,
                    "netto" => $invoice->total_amount_without_tax ?? 0,
                    'createdAt' => Carbon::parse($invoice->created_at)->toDateString(),
                    'updatedAt' => Carbon::parse($invoice->created_at)->toDateString(),
                    'performanceRecordId' => $invoice->performanceRecord?->id,
                    'invoiceEmail' => $invoice->company?->invoice_email ?? "",
                    'category' => $invoice->invoice_category,
                    'userId' => $invoice->user_id,
                    'userName' => $invoice->user?->full_name,
                    'termOfPayment' => $invoice->termsOfPayment ? [
                        'id' => $invoice->termsOfPayment->id,
                        'name' => $invoice->termsOfPayment->name,
                        'paymentTerms' => $invoice->termsOfPayment->payment_terms,
                    ] : null,
                    'startDateExpression' => $invoice->start_date_expression,
                    'endDateExpression' => $invoice->end_date_expression,
                    'createDateExpression' => $invoice->create_date_expression,
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
        return response()->json(['invoiceTemplates' => $invoice, 'totalSum' => $invoice->sum('netto')], 200);
        //return response()->json(['invoiceTemplates' => $invoice], 200);
    }

    /**
     * Create invoice template.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.articleNumber' => 'required|string',
            'products.*.quantity' => ['required'],
            'products.*.salePrice' => ['nullable'],
            'products.*.discount' => ['required'],
            'products.*.totalPrice' => ['required'],
            'customers' => ['required'],
            'invoicePeriod' => 'required',
            'termsOfPayment' => 'required',
            'notes' => 'nullable|string',
            'totalAmount' => 'required',
            'totalAmountWithoutTax' => 'required',
            'totalTaxAmount' => 'required',
            'category' => 'required',
            'userId' => 'required',
            "receiverType" => "required|in:customer,partner"
        ], [
            'products' => 'Please enter products to create an invoice',
            'customers' => 'Please enter an customer to create an invoice',
            'systems' => 'Please enter a system to create an invoice'
        ]);

        //  dd( $request->products);
        $invoice_id = null;
        $invoice = new InvoiceTemplate();
        try {
            DB::transaction(function () use ($request, $invoice) {
                $invoice->receiver_type = $request->receiverType;
                $invoice->invoice_period = isset($request->invoicePeriod['id']) ? $request->invoicePeriod['id'] : null;
                $invoice->terms_of_payment = $request->termsOfPayment;
                $invoice->custom_notes_fields = $request->customNotesFields;
                $invoice->total_amount = $request->totalAmount;
                $invoice->total_amount_without_tax = $request->totalAmountWithoutTax;
                $invoice->total_tax_amount = $request->totalTaxAmount;
                $invoice->invoice_category = $request->category;
                $invoice->user_id = $request->userId;
                $invoice->company_id = $request->customers['id'];
                $invoice->system_id = isset($request->systems['id']) ? $request->systems['id'] : null;
                $invoice->project_id = $request->projectId;
                $invoice->group_by = $request->groupedBy;
                $invoice->payment_terms = $request->paymentTerms;
                $invoice->contact_person = $request->contactPerson;
                $invoice->apply_reverse_change = $request->applyReverseChange;
                $invoice->start_date_expression = $request->startDateExpression;
                $invoice->end_date_expression = $request->endDateExpression;
                $invoice->create_date_expression = $request->createDateExpression;
                $invoice->save();
            });
        } catch (\Exception $e) {
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
                    'invoice_template_id' => $invoice_id,
                    'product_ids' => $categoryProduct,
                    'category_id' => isset($category["id"]) ? $category["id"] : null,
                    'quantity' => isset($category["quantity"]) ? $category["quantity"] : null,
                    'hourly_rate' => isset($category["hourlyRate"]) ? $category["hourlyRate"] : null,
                    'discount' => isset($category["discount"]) ? $category["discount"] : null,
                    'tax' => isset($category["tax"]) ? $category["tax"] : null,
                    'tax_rate' => isset($category["taxRate"]) ? $category["taxRate"] : null,
                    'netto_total' => isset($category["nettoTotal"]) ? $category["nettoTotal"] : null,
                    'product_name' => isset($categoryProductNames) ? $categoryProductNames : null,
                    'additional_description' => isset($category["additionalDescription"]) ? $category["additionalDescription"] : null,
                ];

                InvoiceTemplateCategoryProduct::create($data);
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
                            'hourly_rate' => isset($child['hourlyRate']) ? $child['hourlyRate'] : null,
                            'product_name' => isset($child['name']) ? $child['name'] : null,
                            'price_per_period' => isset($child['pricePerPeriod']) ? $child['pricePerPeriod'] : null,
                            'service_hours' => isset($child['serviceHours']) ? $child['serviceHours'] : null,
                            'maintenance_rate' => isset($child['maintenanceRate']) ? $child['maintenanceRate'] : null,
                            'payment_period' => isset($child['paymentPeriod']) ? (isset($child['paymentPeriod']["id"]) ? $child['paymentPeriod']["id"] : $child['paymentPeriod']) : null,
                            'duration' => isset($child['duration']) ? $child['duration'] : 1,
                            'additional_description' => isset($child["additionalDescription"]) ? $child["additionalDescription"] : null,
                            'parent_invoice_template_product_id' => $product_invoice->pivot->id
                        ]);
                    }
                }
            }
        }
        return response()->json(['message' => 'Record successfully created.', "id" => $invoice_id], 200);
    }


    /**
     * Show invoice template based on id. Its products,system and customer.
     *
     * @param int $iid
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = InvoiceTemplate::findOrFail($id);
        $data = [
            'pInvoices' => [
                'id' => $invoice->id,
                'receiverType' => $invoice->receiver_type,
                'invoicePeriod' => $invoice->invoice_period,
                'invoicePeriodName' => $invoice->paymentPeriod ? $invoice->paymentPeriod->name : "-",
                'termsOfPayment' => $invoice->terms_of_payment,
                'termsOfPaymentName' => $invoice->termsOfPayment ? $invoice->termsOfPayment->name . " | " .
                    $invoice->termsOfPayment->payment_terms : "-",
                'termOfPaymentName' => $invoice->termsOfPayment?->name,
                'notes' => $invoice->custom_notes_fields,
                'project' => $invoice?->project ?? null,
                'groupedBy' => $invoice->group_by ?? null,
                'paymentTerms' => $invoice->payment_terms ?? null,
                'performanceRecord' => $invoice->performance_record_id,
                'performanceRecordObject' => $invoice->performanceRecord,
                'totalAmountWithoutTax' => $invoice->total_amount_without_tax,
                'totalTaxAmount' => $invoice->total_tax_amount,
                'totalAmount' => $invoice->total_amount,
                'createdAt' => Carbon::parse($invoice->created_at)->toDateString(),
                'contactPerson' => $invoice->contact_person,
                'applyReverseChange' => $invoice->apply_reverse_change ?? 0,
                'startDateExpression' => $invoice->start_date_expression,
                'endDateExpression' => $invoice->end_date_expression,
                'createDateExpression' => $invoice->create_date_expression,
                'products' => $invoice->products()->whereNull('parent_invoice_template_product_id')->get()->map(function ($product) {
                    $payment_period = PaymentPeriod::find($product->pivot->payment_period);
                    return [
                        'id' => $product->id,
                        'productInvoiceId' => $product->pivot->id, //productInvoiceId is being used for additional description toggling purposes since it is unique
                        'name' => $product->pivot->product_name ?? $product->name,
                        'isProductNameEdit' => $product->is_product_name_edit,
                        'description' => $product->description,
                        'articleNumber' => $product->article_number,
                        'salePrice' => floatval($product->pivot->sale_price ?? 0),
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
                                'isProductNameEdit' => $child_product->is_product_name_edit,
                                'productInvoiceId' => $child?->id, //productInvoiceId is being used for additional description toggling purposes since it is unique
                                'name' => $child?->product_name ?? $child_product?->name,
                                'description' => $child_product?->description,
                                'articleNumber' => $child_product?->article_number,
                                'salePrice' => floatval($child->sale_price),
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
                'category' => $invoice->invoice_category,
                'userId' => $invoice->user_id,
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
            'modelName' => class_basename($invoice),
        ];

        if ($invoice->invoice_category == 'service' && $invoice->group_by == 'category') {
            $data['pInvoices']['categories'] = $invoice->invoiceProductGroupByCategory->map(function ($item) {
                $products = [];
                foreach ($item->product_ids as $index => $product_id) {
                    $product = Product::findOrFail($product_id);
                    if (!empty($item->product_name)) {
                        $product_name = $item->product_name[$index];
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
                            'salePrice' => floatval($product->sale_price),
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
                    // 'name' => $item->product_name,
                    'products' => $products
                ];
            });
        }
        return response()->json($data);
    }

    /**
     * Update invoice template on base of id.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            /*  'products' => 'required|array',
             'products.*.articleNumber' => 'required|string',
             'products.*.quantity' => ['required'],
             'products.*.salePrice' => ['required'],
             'products.*.discount' => ['required'],
             'products.*.totalPrice' => ['required'], */
            'customers' => ['required'],
            'invoicePeriod' => 'required',
            'termsOfPayment' => 'required',
            'notes' => 'nullable|string',
            'totalAmount' => 'required',
            'totalAmountWithoutTax' => 'required',
            'totalTaxAmount' => 'required',
            'category' => 'required',
            'userId' => 'required',
            "receiverType" => "required|in:customer,partner"
        ], [
            //'products' => 'Please enter products to create an invoice',
            'customers' => 'Please enter an customer to create an invoice',
            'systems' => 'Please enter a system to create an invoice'
        ]);

        try {

            $invoice = InvoiceTemplate::find($id);
            DB::transaction(function () use ($request, $id, &$invoice) {
                $invoice->receiver_type = $request->receiverType;
                $invoice->invoice_period = $request->invoicePeriod['id'];
                $invoice->terms_of_payment = $request->termsOfPayment;
                $invoice->custom_notes_fields = $request->customNotesFields;
                $invoice->company_id = $request->customers['id'];
                $invoice->system_id = isset($request->systems['id']) ? $request->systems['id'] : null;
                $invoice->contact_person = $request->contactPerson;
                $invoice->apply_reverse_change = $request->applyReverseChange ?? 0;
                $invoice->total_amount = $request->totalAmount;
                $invoice->total_amount_without_tax = $request->totalAmountWithoutTax;
                $invoice->total_tax_amount = $request->totalTaxAmount;
                $invoice->invoice_category = $request->category;
                if (empty($request->userId)) {
                    $user_id = $this->getAuthUserId($request);
                    $invoice->user_id = $user_id; // Shift invoice to admin if the original create user does not exist
                }
                $invoice->project_id = $request->projectId;
                $invoice->group_by = $request->groupedBy;
                $invoice->payment_terms = $request->paymentTerms;
                $invoice->start_date_expression = $request->startDateExpression;
                $invoice->end_date_expression = $request->endDateExpression;
                $invoice->create_date_expression = $request->createDateExpression;
                if ($request->category == 'service' && $request->groupedBy == 'category') {
                    $products = collect($request->products);
                    $invoiceTempalteProductRecord = InvoiceTemplateCategoryProduct::where('invoice_template_id', $invoice->id);
                    $invoiceTempalteProductNames = '';
                    if ($invoiceTempalteProductRecord->first()) {
                        $record = $invoiceTempalteProductRecord->first();
                        $invoiceTempalteProductNames = $record->product_name;
                        $product_ids = $record->product_ids;
                    }

                    $invoiceTempalteProductRecord->delete();

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
                            'invoice_template_id' => $invoice->id,
                            'product_ids' => $categoryProduct,
                            'category_id' => isset($category["id"]) ? $category["id"] : null,
                            'quantity' => isset($category["quantity"]) ? $category["quantity"] : null,
                            'hourly_rate' => isset($category["hourlyRate"]) ? $category["hourlyRate"] : null,
                            'discount' => isset($category["discount"]) ? $category["discount"] : null,
                            'tax' => isset($category["tax"]) ? $category["tax"] : null,
                            'tax_rate' => isset($category["taxRate"]) ? $category["taxRate"] : null,
                            'netto_total' => isset($category["nettoTotal"]) ? $category["nettoTotal"] : null,
                            'product_name' => isset($categoryProductNames) ? $categoryProductNames : null,
                            'additional_description' => isset($category["additionalDescription"]) ? $category["additionalDescription"] : null,
                        ];
                        InvoiceTemplateCategoryProduct::create($data);
                    }
                } else {
                    $invoice->products()->detach();
                    foreach ($request->products as $product) {
                        // attach the product to the invoice
                        $invoice->products()->attach($product['id'], [
                            'sale_price' => $product['salePrice'], 'discount' => $product['discount'],
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
                                    'sale_price' => $child['salePrice'], 'discount' => $child['discount'],
                                    'quantity' => $child['quantity'], 'total_price' => isset($child['totalPrice']) ? $child['totalPrice'] : $child['totalRate'],
                                    'price_without_tax' => isset($child['nettoTotal']) ? $child['nettoTotal'] : 0,
                                    'tax' => isset($child['tax']) ? $child['tax'] : '',
                                    'hourly_rate' => isset($child['hourlyRate']) ? $child['hourlyRate'] : null,
                                    'product_name' => isset($child['name']) ? $child['name'] : null,
                                    'price_per_period' => isset($child['pricePerPeriod']) ? $child['pricePerPeriod'] : null,
                                    'service_hours' => isset($child['serviceHours']) ? $child['serviceHours'] : null,
                                    'maintenance_rate' => isset($child['maintenanceRate']) ? $child['maintenanceRate'] : null,
                                    'payment_period' => isset($child['paymentPeriod']) ? (isset($child['paymentPeriod']["id"]) ? $child['paymentPeriod']["id"] : $child['paymentPeriod']) : null,
                                    'duration' => isset($child['duration']) ? $child['duration'] : 1,
                                    'additional_description' => isset($child["additionalDescription"]) ? $child["additionalDescription"] : null,
                                    'parent_invoice_template_product_id' => $product_invoice->pivot->id
                                ]);
                            }
                        }
                    }
                }
                $invoice->save();
            });
            return response()->json([
                'message' => 'Record successfully updated.',
                'invoiceNumber' => $invoice?->invoice_number
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 422);
        }
    }

    public function destroy($id)
    {
        $model = InvoiceTemplate::find($id);
        $model->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    public function createInvoice($id)
    {
        $invoiceTemplate = InvoiceTemplate::find($id);
        if ($invoiceTemplate) {
            $invoice = new Invoice();
            DB::transaction(function () use ($invoiceTemplate, $invoice) {
                $invoice->grouped_by = $invoiceTemplate->group_by;
                $invoice->payment_terms = $invoiceTemplate->payment_terms;
                $invoice->invoice_category = $invoiceTemplate->invoice_category;
                $invoice->start_date = Carbon::parse(strtotime($invoiceTemplate->start_date_expression));
                $invoice->end_date = Carbon::parse(strtotime($invoiceTemplate->end_date_expression));
                $invoice->company_id = $invoiceTemplate->company_id;
                $invoice->user_id = $invoiceTemplate->user_id;
                $invoice->system_id = $invoiceTemplate->system_id;
                $invoice->invoice_period = $invoiceTemplate->invoice_period;
                $invoice->project_id = $invoiceTemplate->project_id;
                $invoice->performance_record_id = $invoiceTemplate->performance_record_id;
                $invoice->total_amount = $invoiceTemplate->total_amount;
                $invoice->total_amount_without_tax = $invoiceTemplate->total_amount_without_tax;
                $invoice->total_tax_amount = $invoiceTemplate->total_tax_amount;
                $invoice->terms_of_payment = $invoiceTemplate->terms_of_payment;
                $invoice->custom_notes_field = $invoiceTemplate->custom_notes_fields;
                $invoice->contact_person = $invoiceTemplate->contact_person;
                $invoice->apply_reverse_change = $invoiceTemplate->apply_reverse_change;
                $invoice->receiver_type = $invoiceTemplate->receiver_type;
                $invoice->save();

                if ($invoiceTemplate->invoice_category == "service" && $invoiceTemplate->performance_record_id) {
                    $performance_record = PerformanceRecord::findOrFail($invoiceTemplate->performance_record_id);
                    $performance_record->invoice_id = $invoice->id;
                    $performance_record->save();
                    $invoice->performance_record_id = $performance_record->id;
                    $invoice->save();
                }

                if ($invoice->invoice_category == 'service' && $invoice->grouped_by == 'category') {
                    foreach ($invoiceTemplate->invoiceProductGroupByCategory as $categoryProduct) {
                        $data = [
                            'invoice_id' => $invoice->id,
                            'product_ids' => $categoryProduct->product_ids,
                            'category_id' => $categoryProduct->category_id,
                            'product_names' => $categoryProduct->product_name,
                            'quantity' => $categoryProduct->quantity,
                            'hourly_rate' => $categoryProduct->hourly_rate,
                            'discount' => $categoryProduct->discount,
                            'tax' => $categoryProduct->tax,
                            'tax_rate' => $categoryProduct->tax_rate,
                            'netto_total' => $categoryProduct->netto_total,
                            'additional_description' => $categoryProduct->additional_description,
                        ];
                        ProductInvoiceCategory::create($data);
                    }
                } else {
                    foreach ($invoiceTemplate->parentProducts as $product) {
                        $this->storeInvoiceProduct($invoice, $product);
                        $productInvoice = $invoice->products()->latest()->first();
                        foreach ($product->pivot->children as $childProduct) {
                            $this->storeInvoiceProduct($invoice, $childProduct, $productInvoice->id);
                        }
                    }
                }
            });
            return response()->json(['message' => 'Invoice created successfully', 'invoiceId' => $invoice->id]);
        }
        return response()->json(['message' => 'Invalid id provided']);
    }


    private function storeInvoiceProduct($invoice, $product, $parentId = null): void
    {
        $invoice->products()->attach($product->pivot->product_id, [
            'sale_price' => $product->pivot->sale_price,
            'discount' => $product->pivot->discount,
            'quantity' => $product->pivot->quantity,
            'total_price' => $product->pivot->total_price,
            'price_without_tax' => $product->pivot->price_without_tax,
            'tax' => $product->pivot->tax,
            'hourly_rate' => $product->pivot->hourly_rate,
            'product_name' => $product->pivot->product_name,
            'price_per_period' => $product->pivot->price_per_period,
            'service_hours' => $product->pivot->service_hours,
            'maintenance_rate' => $product->pivot->maintenance_rate,
            'payment_period' => $product->pivot->payment_period,
            'duration' => $product->pivot->duration,
            'additional_description' => $product->pivot->additional_description,
            'parent_product_invoice_id' => $parentId,
        ]);
    }
}
