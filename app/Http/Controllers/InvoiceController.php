<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Helpers\InvoiceAndTemplateHelper;
use App\Models\Company;
use App\Models\GlobalSetting;
use App\Models\Invoice;
use App\Models\Product;
use App\Enums\InvoiceType;
use App\Models\ExportedCsvInfo;
use App\Models\System;
use App\Traits\CustomHelper;
use Facades\App\Services\InstallSystemService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Net\SSH2 as NetSSH2;
use phpseclib3\Net\SFTP as NetSFTP;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{

    use CustomHelper;

    public $invoiceService;

    /**
     * Runs when instance is initiated
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->middleware('check.permission:invoice.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:invoice.create', ['only' => ['store']]);
        $this->middleware('check.permission:invoice.edit', ['only' => ['update']]);
        $this->middleware('check.permission:invoice.delete', ['only' => ['destroy']]);
        $this->middleware('check.permission:finance-dashboard', ['only' => ['invoiceStatistics']]);
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display invoice table via pagination.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return InvoiceAndTemplateHelper::index($request, 0);
    }

    /**
     * Display all invoices for references.
     * @return \Illuminate\Http\Response
     */
    public function getReferenceInvoices($id = '')
    {
        return InvoiceAndTemplateHelper::referenceInvoices($id, 0);
    }



    /**
     * Return data for create page of invoice with paginated products and all customers.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            // by default filter the products who have a default price list set
            'products' => Product::whereHas('productPrice', function ($query) {
                $query->where('is_default', 1);
            })->whereIn("payment_details_type", ["software", "pauschal"])->orderBy('article_number')
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
                    'customerNumber' => $company->company_number,
                    'url' => $company->url,
                    'name' => $company->company_name,
                    'code' => $company->headQuarters?->zip,
                    'city' => $company->headQuarters?->city,
                    'country' => $company->headQuarters?->country,
                    'address' => $company->headQuarters?->address_first,
                    'termsOfPayment' => $company->terms_of_payment,
                ]),
            'invoiceTypes' => InvoiceType::ALL
        ], 200);
    }

    /**
     * Create invoice.
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
            'invoiceType' => 'required|string',
            'invoicePeriod' => 'required',
            'termsOfPayment' => 'required',
            'notes' => 'nullable|string',
            'dueDate' => 'required|string',
            'totalAmount' => 'required',
            'totalAmountWithoutTax' => 'required',
            'totalTaxAmount' => 'required',
            'category' => 'required',
            'userId' => 'required',
            "startDate" => "required",
            "endDate" => "required",
            "receiverType" => "required|in:customer,partner"
        ], [
            'products' => 'Please enter products to create an invoice',
            'customers' => 'Please enter an customer to create an invoice',
            'systems' => 'Please enter a system to create an invoice'
        ]);

        return InvoiceAndTemplateHelper::store($request, 0);
    }

    /**
     * Fetch data for invoice object. Its products,system and customer .
     *
     * @param \Illuminate\Http\Request $request
     * @param object $invoice
     * @return \Illuminate\Http\Response
     */
    public function getStoreNo(Invoice $invoice, Request $request)
    {
        return response()->json([
            'pInvoices' => [
                'invoiceType' => $invoice->invoice_type,
                'dueDate' => Carbon::parse($request->dueDate),
                'invoicePeriod' => $invoice->invoice_period,
                'termsOfPayment' => $invoice->terms_of_payment,
                'notes' => $invoice->notes,
                'products' => $invoice->products->map(fn ($product) => [
                    'name' => $product->name,
                    'id' => $product->id,
                    'articleNumber' => $product->article_number,
                    'salePrice' => $product->pivot->sale_price ?? 0,
                    'discount' => $product->pivot->discount,
                    'quantity' => $product->pivot->quantity,
                    'totalPrice' => 0 - $product->pivot->total_price,
                    'priceWithoutTax' => 0 - $product->pivot->price_without_tax,
                    'tax' => $product->pivot->tax,
                ]),
                'systems' => [
                    'id' => $invoice->systems->id,
                    'systemNumber' => $invoice->systems->system_number
                ],
                'customer_city' => $invoice->company->headQuarters?->city,
                'customer_country' => $invoice->company->headQuarters?->country,
                'customer_code' => $invoice->company->headQuarters?->zip,
                'customer_address' => $invoice->company->headQuarters?->address_first,
                'invoiceStatus' => $invoice->status,
                'customer' => $invoice->company,
                'customer_state' => $invoice->company->headQuarters?->state,
                'customer_country' => $invoice->company->headQuarters?->country,
                'created_dt' => Carbon::parse($invoice->created_at)->format('Y-m-d')
            ]
        ], 200);
    }

    /**
     * Fetch data for invoice object for type correction. Its products,system and customer.
     *
     * @param \Illuminate\Http\Request $request
     * @param object $invoice
     * @return \Illuminate\Http\Response
     */
    public function getCorrection(Invoice $invoice, Request $request)
    {
        return response()->json([
            'isCorrection' => true,
            'pInvoices' => [
                'id' => $invoice->id,
                'invoiceType' => 'invoice-correction',
                'dueDate' => Carbon::parse($request->dueDate),
                'invoicePeriod' => $invoice->invoice_period,
                'termsOfPayment' => $invoice->terms_of_payment,
                'notes' => $invoice->notes,
                'products' => $invoice->products->map(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'articleNumber' => $product->article_number,
                    'salePrice' => floatval($product->pivot->sale_price) ?? 0,
                    'discount' => floatval($product->pivot->discount),
                    'quantity' => floatval($product->pivot->quantity),
                    'totalPrice' => floatval($product->pivot->total_price),
                    'tax' => +$product->pivot->tax,
                    'priceWithoutTax' => floatval($product->pivot->price_without_tax)
                ]),
                'customer' => $invoice->company,
                'systems' => [
                    'id' => $invoice->systems->id,
                    'systemNumber' => $invoice->systems->system_number
                ],

                'customer_city' => $invoice->company->headQuarters?->city,
                'customer_country' => $invoice->company->headQuarters?->country,
                'customer_code' => $invoice->company->headQuarters?->zip,
                'customer_address' => $invoice->company->headQuarters?->address_first,
                'invoiceStatus' => $invoice->status
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
            'invoiceTypes' => InvoiceType::ALL
        ], 200);
    }

    /**
     * Show invoice based on id. Its products,system and customer.
     *
     * @param int $iid
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return InvoiceAndTemplateHelper::show($id);
    }

    /**
     * Update invoice on base of id.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return InvoiceAndTemplateHelper::update($request, $id);
    }

    /**
     * gets elo request data for invoice
     * @param int $id
     * @return JSONResponse
     */
    public function getELORequestData($id)
    {
        $invoice = Invoice::findOrFail($id);
        return response()->json([
            'data' => $invoice->toArray(),
        ]);
    }


    /**
     * Get paginated system on base of customer id.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getCustomerSpecifiedSystems(Request $request)
    {
        $system = System::where('customer_id', $request->id)->paginate(25)->through(fn ($system) => [
            'id' => $system->id,
            'serverIp' => $system->server_ip,
            'type' => $system->type,
            'serverPort' => $system->server_port,
            'username' => $system->username,
            'systemNumber' => $system->system_number
        ]);
        return response()->json($system, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Execute Secure Socket Shell on base of invoice object.
     *
     * @param object $invoice
     * @return \Illuminate\Http\Response
     */
    public
    function executeSSH(Invoice $invoice)
    {
        return response()->stream(function () use ($invoice) {
            try {
                $products = $invoice->products;
                $system = $invoice->systems;
                $host = $system->server_ip;
                $port = $system->server_port ?? 22;
                $username = $system->username;
                $password = $system->password;
                $ssh = new NetSSH2($host, $port);
                if (!$ssh->login($username, $password)) {
                    echo 'data: Not connected either username or password is incorrect';
                    /** use to stream the data */
                    ob_flush();
                    flush();
                    echo "\n\n";
                    /** */
                    return;
                }
                $sftp = new NetSFTP($host, $port);
                if (!$sftp->login($username, $password)) {
                    echo 'data: Not connected either username or password is incorrect';
                    /** use to stream the data */
                    ob_flush();
                    flush();
                    echo "\n\n";
                    /** */
                    return;
                }
                if (isset($products)) {
                    foreach ($products as $product) {
                        $product_version = $product->versions()->latest()->first();
                        //uploading files using sftp
                        if ($product_version->files->count() ?? false) {
                            foreach ($product_version->files as $file) {
                                $message = InstallSystemService::uploadFiles($sftp, $file, $invoice->systems->operating_system);
                                echo 'data:' . $message['message'] . ' for product ' . $product->name;
                                /** use to stream the data */
                                ob_flush();
                                flush();
                                echo "\n\n";
                            }
                        } else {
                            echo 'data:' . 'No file found for product ' . $product->name;
                            /** use to stream the data */
                            ob_flush();
                            flush();
                            echo "\n\n";
                        }
                        //executing environment variables
                        if ($product_version->productParameters->count() ?? false) {
                            foreach ($product_version->productParameters as $param) {
                                $message = InstallSystemService::executeProductParams($ssh, $param);
                                echo 'data:' . $message['message'] . ' for product ' . $product->name;
                                /** use to stream the data */
                                ob_flush();
                                flush();
                                echo "\n\n";
                            }
                        } else {
                            echo 'data:' . 'No params found for product ' . $product->name;
                            /** use to stream the data */
                            ob_flush();
                            flush();
                            echo "\n\n";
                        }
                        $message = InstallSystemService::executeSystemCommands($ssh, $product_version, $system);
                        echo 'data:' . $message['message'] . ' for product ' . $product->name;
                        /** use to stream the data */
                        ob_flush();
                        flush();
                        echo "\n\n";
                        //executing commands
                    }
                }
            } catch (Exception $e) {
                echo 'data: An exception has occured while executing ' . $e->getMessage();
                /**use to stream the data */
                ob_flush();
                flush();
                echo "\n\n";
                /** */
                return;
            }
            return;
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
        return response()->json(['message' => 'Commands successfully Executed.'], 200);
    }

    /**
     * Execute commands on Secure Socket Shell on base of invoice object.
     *
     * @param object $invoice
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function executeCustomCommands(Request $request, Invoice $invoice)
    {
        return response()->stream(function () use ($request, $invoice) {
            try {
                $system = $invoice->systems;
                $host = $system->server_ip;
                $port = $system->server_port ?? 22;
                $username = $system->username;
                $password = $system->password;
                $ssh = new NetSSH2($host, $port);
                if (!$ssh->login($username, $password)) {
                    echo 'data: Not connected either username or password is incorrect';
                    /** use to stream the data */
                    ob_flush();
                    flush();
                    echo "\n\n";
                    /** */
                    return;
                }
                $commands_to_execute = json_decode($request->command);
                foreach ($commands_to_execute as $command) {
                    $result = $ssh->exec($command);
                    $getData = array_filter(explode("\n", $result));
                    echo 'data:Command successfully executed. Result:' . json_encode($getData);
                    ob_flush();
                    flush();
                    echo "\n\n";
                }
            } catch (Exception $e) {
                echo 'data: An exception has occured while executing ' . $e->getMessage();
                /**use to stream the data */
                ob_flush();
                flush();
                echo "\n\n";
                /** */
                return;
            }
            return;
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
    }

    /**
     * Generate invoice pdf with products and customers in it.
     *
     * @param object $invoice
     * @return \Illuminate\Http\Response
     */
    private
    function generateInvoicePdf(Invoice $invoice)
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

    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function getInvoiceByCompany(Request $request)
    {

        $model = new Invoice;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->where("company_id", $request->id)->orderBy('invoices.created_at')->get();
        $data = $model->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'invoiceNumber' => $invoice->invoice_number,
                'company' => $invoice->company->company_name,
                'notes' => $invoice->custom_notes_field,
                'invoiceType' => $invoice->invoice_type,
                'invoicePeriod' => $invoice->invoice_period,
                'dueDate' => Carbon::parse($invoice->due_date)->toDateString()
            ];
        });
        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    /**
     * delete invoice on base of id.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return InvoiceAndTemplateHelper::destroy($id);
    }

    /**
     * Downloads invoice csv.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function downloadCSV(Request $request)
    {

        $file_name = 'invoices.csv';
        $invoices = new Invoice;
        if ($request->companyId) {
            $invoices = $invoices->where('company_id', $request->companyId);
        }
        if ($request->status) {
            $invoices = $invoices->where('status', $request->status);
        }

        if ($request->forCustomerPortal) {
            $invoices = $invoices->whereNotIn('status', ['draft', 'approved']);
        }

        $invoices = $invoices->get();
        return $this->createCSV($invoices, $file_name, 'invoice');
    }

    /**
     * Download latest csv created of invoices.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function downloadLatestCSV(Request $request)
    {
        $file_name = 'invoices.csv';
        $exported_csv = ExportedCsvInfo::where('exported_csv_name', 'invoice')->first();
        if (empty($exported_csv)) return $this->exportCsv($request);
        $exported_csv_time = Carbon::parse($exported_csv->exported_time);
        $invoices =
            Invoice::where('updated_at', '>', $exported_csv_time)
            ->orWhereHas('company', function ($query) use ($exported_csv_time) {
                $query->where('updated_at', '>', $exported_csv_time);
            });
        if ($request->companyId) {
            $invoices = $invoices->where('company_id', $request->companyId);
        }
        if ($request->status) {
            $invoices = $invoices->where('status', $request->status);
        }
        $invoices = $invoices->get();
        return $this->createCSV($invoices, $file_name, 'invoice', true);
    }

    /**
     * Create csv of selected invoices.
     *
     * @param string $type which is invoice type
     * @param string $file_name which is name we want to create the file
     * @param object $invoices which is list of selected invoices
     * @param boolean $is_latest_exported_csv
     * @return \Illuminate\Http\Response
     */
    private
    function createCSV($invoices, $file_name, $type, $is_latest_exported_csv = false)
    {
        $columns = [
            'Invoice Number', 'Invoice Type', 'Status', 'Total Amount', 'Total Amount Without Tax',
            'Customer Name', 'Customer Phone'
        ];
        $callback = function () use ($invoices, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($invoices as $invoice) {
                $row['Invoice Number'] = $invoice->invoice_number;
                $row['Invoice Type'] = $invoice->invoice_type;
                $row['Status'] = $invoice->status;
                $row['Total Amount'] = number_format($invoice->total_amount, 2, ',', '.');
                $row['Total Amount Without Tax'] =  number_format($invoice->total_amount_without_tax, 2, ',', '.');
                $row['Customer Name'] = $invoice->company->company_name ?? '';
                $row['Customer Phone'] = $invoice->company->phone ?? '';

                fputcsv($file, [
                    $row['Invoice Number'], $row['Invoice Type'],
                    $row['Status'], $row['Total Amount'], $row['Total Amount Without Tax'], $row['Customer Name'],
                    $row['Customer Phone']
                ]);
            }
            fclose($file);
        };
        if (!$is_latest_exported_csv) {
            $exported_csv = ExportedCsvInfo::firstOrNew(['exported_csv_name' => $type]);
            $exported_csv->exported_time = Carbon::now();
            $exported_csv->save();
        }
        return response()->streamDownload($callback, $file_name, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$file_name",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }

    /**
     * retrieves the invoice stats based on the filters received in request
     * @param Request, received request
     * @return Response, response with the statistics
     */
    public function invoiceStatistics(Request $request)
    {
        // filter based on month and year
        $invoices = Invoice::whereYear('invoices.start_date', $request->year);


        if ($request->month) {
            $invoices->whereMonth('invoices.start_date', $request->month);
        }

        // filter by invoice status
        if ($request->invoiceStatus) {
            $invoices->where('invoices.status', $request->invoiceStatus);
        }

        // filter based on company_id
        if ($request->company) {
            $invoices->where('company_id', $request->company);
        }

        // filter based on invoice_category
        if ($request->category) {
            $invoices->where('invoice_category', $request->category);
        }

        $per_page = $request->perPage ?? 25;
        $invoice_copy = clone $invoices; // clone the $invoices because we want the where condition below to be applied just for listing
        // filter based on companyId
        if ($request->companyId) {
            $invoice_listing = $invoice_copy->where('company_id', $request->companyId);
        }


        // paginate the invoices data
        $models = $invoice_copy->paginate($per_page)->withQueryString();

        // map the invoice listing
        $model_collect = $models->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'invoiceNumber' => $invoice->invoice_number,
                'companyName' => $invoice->company?->company_name,
                'invoiceType' => $invoice->invoice_type,
                'category' => $invoice->invoice_category,
                'status' => $invoice->status,
                'netto' => $invoice->total_amount_without_tax,
                'createdAt' => Carbon::parse($invoice->created_at)->toDateString(),
                'dueDate' => Carbon::parse($invoice->due_date)->toDateString()
            ];
        });
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');

        if ($sort_by && $sort_order) {
            $model_collect = $this->applySorting($model_collect, $sort_by, $sort_order);
        }

        // mapped and paginated invoices listing
        $invoice_listing = [
            'data' => $model_collect, 'links' => $models->links(),
            'meta' => [
                'current_page' => $models->currentPage(),
                'from' => $models->firstItem(),
                'last_page' => $models->lastPage(),
                'path' => request()->url(),
                'per_page' => $models->perPage(),
                'to' => $models->lastItem(),
                'total' => $models->total(),
            ],
        ];

        /**
         * join the invoices with companies table and retrieve the company_name and company_id
         * sum the total_amount_without_tax and total_amount
         * group the records by company_name and company_id
         */
        $stats = $invoices->selectRaw('invoices.company_id AS companyId, companies.company_name AS companyName, SUM(invoices.total_amount_without_tax) AS netTotal, SUM(invoices.total_amount) AS grossTotal')
            ->join('companies', 'invoices.company_id', '=', 'companies.id')
            ->groupBy('invoices.company_id', 'companies.company_name')->get();

        return response()->json([
            'statistics' => $stats,
            'totalNetTotal' => $stats->sum('netTotal'), // get net total across invoices
            'totalGrossTotal' => $stats->sum('grossTotal'), // get gross total across invoices
            'invoiceListing' => $invoice_listing
        ]);
    }

    /**
     * updated the invoice status
     * @param {$request} Request
     * @param {$id} id of the invoice to be updated
     */
    public function updateInvoiceStatus(Request $request, $id)
    {

        $request->validate([
            "status" => "required|in:approved,sent,paid,warning level 1,warning level 2,warning level 3"
        ]);

        $invoice = Invoice::findOrFail($id);

        if ($invoice->status != InvoiceStatus::DRAFT) {
            $invoice->status = $request->status;
            $invoice->save();
        }

        return response()->json([
            "success" => true,
            "message" => "Status has been updated sucessfully"
        ]);
    }

    /**
     * generate the payload for document generation for invoice and connected performance record
     * @param integer $id
     * @return array
     */
    public function documentGeneration($id)
    {
        $invoice = Invoice::findOrFail($id);

        // generate the payload of invoice for document generation
        $invoice_data = $this->invoiceService->invoiceForDocumentGeneration($invoice);

        // Retrieve the performance record
        $performance_record = $invoice->performanceRecord ? $invoice->performanceRecord()->with([
            'performanceRecordEntries' => function ($query) {
                $query->orderByRaw('CAST(entry_order AS DECIMAL(65,30)) ASC');
            },
            'performanceRecordEntries.timeTrackerCompany'
        ])->first() : null;

        // generate the payload of performance record for document generation
        $performance_record_data = $this->invoiceService->performanceRecordForDocumentGeneration($performance_record);

        $invoice_template_enums = [
            "invoice" => "invoiceTemplateId",
            "invoice-correction" => "invoiceCorrectionTemplateId",
            "invoice-storno" => "invoiceStornoTemplateId",
        ];

        $invoiceTemplateId = GlobalSetting::where("key", $invoice_template_enums[$invoice->invoice_type])?->first()?->value;

        if (!$invoiceTemplateId) {
            return response()->json([
                "message" => "Please assign a template document for invoice module"
            ], 422);
        }

        $perfRecordTemplateId = GlobalSetting::where("key", "perfRecordTemplateId")?->first()?->value;

        if (!$perfRecordTemplateId) {
            return response()->json([
                "message" => "Please assign a template document for performance record module"
            ], 422);
        }

        return response()->json([
            "invoice" => $invoice_data,
            "performanceRecord" => $performance_record_data,
            "invoiceTemplateId" => $invoiceTemplateId,
            "perfRecordTemplateId" => $perfRecordTemplateId,
        ]);
    }
}
