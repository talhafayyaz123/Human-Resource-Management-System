<?php

namespace App\Http\Controllers;

use App\Helpers\CustomerHelper;
use App\Helpers\GlobalSettingHelper;
use App\Http\Resources\ModuleHistoryResource;
use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\CompanyUsers;
use App\Models\ExportedCsvInfo;
use App\Models\GlobalSetting;
use App\Models\UploadedFile;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as staticRequest;
use App\Traits\SetGlobalNumber;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    use SetGlobalNumber, CustomHelper;

    protected $customerHelper;

    /**
     * Run on instantiate
     */
    public function __construct(CustomerHelper $customerHelper)
    {
        $this->middleware('check.permission:company.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:company.create', ['only' => ['store']]);
        $this->middleware('check.permission:company.edit', ['only' => ['update']]);
        $this->middleware('check.permission:company.delete', ['only' => ['destroy']]);
        $this->customerHelper = $customerHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/companies",
     *      operationId="getCompaniesList",
     *      tags={"Company"},
     *      summary="Get list of companies",
     *      description="Returns list of companies",
     *      @OA\Parameter(
     *          name="perPage",
     *          description="Get records in one page default is 10",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     *     @OA\Parameter(
     *          name="sortBy",
     *          description="Sort by column",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *      ),
     *
     *      @OA\Parameter(
     *         name="sortOrder",
     *          description="Sort order (asc, desc)",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *      ),
     *     @OA\Parameter(
     *         name="search",
     *          description="Search in different column records",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *      ),
     *     @OA\Parameter(
     *         name="isSystem",
     *          description="Get order by customer type",
     *          in="query",
     *          @OA\Schema(
     *              type="boolen"
     *          ),
     *      ),
     *     @OA\Parameter(
     *         name="customerType",
     *          description="Get the customer type records",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function index(Request $request)
    {
        return $this->customerHelper->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     *      path="/companies",
     *      operationId="StoreCompany",
     *      tags={"Company"},
     *      summary="Store company records",
     *      description="Store a newly created resource in storage",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Json request",
     *    @OA\JsonContent(
     *       required={"companyName","vatId", "type", "customerType", "addressFirst", "city", "country", "state",
     *                  "zip", "url", "bankDetails"},
     *       @OA\Property(property="companyName", type="string"),
     *       @OA\Property(property="vatId", type="string"),
     *      @OA\Property(property="fax", type="string"),
     *       @OA\Property(property="type", type="string"),
     *       @OA\Property(property="customerType", type="string"),
     *       @OA\Property(property="addressFirst", type="string"),
     *     @OA\Property(property="city", type="string"),
     *     @OA\Property(property="country", type="string"),
     *     @OA\Property(property="state", type="string"),
     *     @OA\Property(property="zip", type="string"),
     *     @OA\Property(property="url", type="string"),
     *       @OA\Property(property="termsOfPayment", type="string"),
     *     @OA\Property(property="invoiceEmail", type="string"),
     *     @OA\Property(property="defaultServiceProduct", type="string"),
     *     @OA\Property(property="defaultServiceHourlyRate", type="string"),
     *     @OA\Property(property="defaultServiceDailyRate", type="string"),
     *     @OA\Property(property="discount", type="string"),
     *     @OA\Property(property="phone", type="string"),
     *     @OA\Property(property="status", type="string"),
     *       @OA\Property(property="notes", type="string"),
     *      @OA\Property(property="size", type="string"),
     *      @OA\Property(property="orderProbability", type="string"),
     *      @OA\Property(property="assignees", type="object", example="[1,2]"),
     *       @OA\Property(property="bankDetails", type="array",
     *          @OA\Items(
     *     type="object",
     *     required={"bankName", "swift", "iban"},
     *                     @OA\Property(property="bankName", type="string"),
     *                     @OA\Property(property="swift", type="string"),
     *                     @OA\Property(property="iban", type="string"),
     *                 )
     *      ),
     *
     *    ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function store(Request $request)
    {
        $request->validate([
            "companyName" => "required|string",
            "fax" => "nullable|string",
            "customerType" => "required",
            "addressFirst" => "required",
            "city" => "required",
            "country" => "required",
            "zip" => "required",
            "url" => "required",
            'defaultPaymentPeriod' => 'required_if:customerType,==,customer',
            "contactSources" => "nullable|array",
            "assignees" => [isset($request->isSeeder) ? 'nullable' : 'required_if:customerType,==,lead', 'array'],
            // "termsOfPayment" => "required", uncomment after seeders are updated
            "bankDetails" => "nullable|array",
            "bankDetails.*.bankName" => "required",
            "bankDetails.*.swift" => "required|string",
            "bankDetails.*.iban" => "required|string",
            "priceLists" => "nullable|array",
            "priceLists.*.id" => "required",
            'partner' => 'required_unless:customerType,partner',
        ], [
            "bankDetails.*.bankName.required" => "The bank name field is required.",
            "bankDetails.*.swift.required" => "The swift field is required.",
            "bankDetails.*.iban.required" => "The iban field is required."
        ]);

        if ($request->customerType == "customer") {
            $request->validate([
                "status" => "required|string",
            ]);
        } else if ($request->customerType == "lead") {
            $request->validate([
                "statusId" => "required|integer|exists:App\Models\LeadStatus,id",
                "vatId" => "required|string"
            ]);
        } else if ($request->customerType == "partner") {
            $request->validate([
                "partnerType" => "required|in:reseller_partner,business_partner",
                'isEloPartner' => 'nullable|boolean'
            ]);
        }

        $company_id = null;
        $company_number = null;
        try {
            DB::transaction(function () use ($request, &$company_id, &$company_number) {
                if (!empty($request->priceLists)) {
                    $price_list_collection = collect($request->priceLists);

                    $duplicateCollection = $price_list_collection->duplicates('productSoftwareId');

                    if ($duplicateCollection->isNotEmpty()) {
                        throw new Exception("Multiple software records exist with software id");
                    }
                }
                //Create Company
                $company = new Company;
                $company->company_name = $request->companyName;
                $company->vat_id = $request->vatId;
                $company->url = $request->url;
                $company->phone = $request->phone;
                $company->fax = $request->fax;
                $company->type = $request->type;
                $company->customer_type = $request->customerType;
                $company->terms_of_payment = $request->termsOfPayment ?? null;
                $company->invoice_email = $request->invoiceEmail;
                $company->default_service_product = $request->defaultServiceProduct;
                $company->default_service_hourly_rate = $request->defaultServiceHourlyRate;
                $company->default_service_daily_rate = $request->defaultServiceDailyRate;
                $company->discount = $request->discount ?? 0;
                $company->default_payment_period = $request->defaultPaymentPeriod ?? null;
                $company->apply_reverse_change = $request->applyReverseChange ?? 0;
                $company->external_order_number = $request->externalOrderNumber ?? 0;
                $company->is_customer_portal_onboarding = $request->isCustomerPortalOnboarding ?? 0;
                $company->partner_id = $request->partner;
                if (isset($request->mergePdfsOnDefault)) {
                    $company->merge_pdfs_on_default = $request->mergePdfsOnDefault;
                }
                $type = 'Customer';
                if ($company->customer_type == 'lead') {
                    $type = 'Lead';
                    $global_invoice_setting = GlobalSetting::where('key', 'lead')->first();
                    if (empty($global_invoice_setting)) {
                        $global_setting = new GlobalSetting;
                        $global_setting->key = 'lead';
                        $global_setting->value = 1000;
                        $global_setting->save();
                    } else {
                        $global_setting = tap(DB::table('global_settings')->where('key', 'lead'))->update([
                            'value' => DB::raw("value+1")
                        ])->first();
                    }
                    $company->company_number = 'L' . $global_setting->value;
                    $company->expiry_dt = isset($request->expiryDate) ? Carbon::parse($request->expiryDate) : null;
                    $company->lead_status_id = $request->statusId;
                    $company->notes = $request->notes;
                    $company->size = $request->size;
                    $company->order_probability = $request->orderProbability;
                } else if ($company->customer_type == 'customer') {
                    $global_invoice_setting = GlobalSetting::where('key', 'company')->first();
                    if (empty($global_invoice_setting)) {
                        $global_setting = new GlobalSetting;
                        $global_setting->key = 'company';
                        $global_setting->value = 1000;
                        $global_setting->save();
                    } else {
                        $global_setting = tap(DB::table('global_settings')->where('key', 'company'))->update([
                            'value' => DB::raw("value+1")
                        ])->first();
                    }
                    $company->company_number = 'C' . $global_setting->value;
                    $company->status = $request->status;
                } else if ($company->customer_type == 'partner') {
                    $global_invoice_setting = GlobalSetting::where('key', 'partner')->first();
                    if (empty($global_invoice_setting)) {
                        $global_setting = new GlobalSetting;
                        $global_setting->key = 'partner';
                        $global_setting->value = 1000;
                        $global_setting->save();
                    } else {
                        $global_setting = tap(DB::table('global_settings')->where('key', 'partner'))->update([
                            'value' => DB::raw("value+1")
                        ])->first();
                    }
                    $company->partner_type = $request->partnerType;
                    $company->company_number = 'P' . $global_setting->value;
                    $company->is_elo_partner = $request->isEloPartner ?? false;
                }

                $company->save();

                if ($request->attachment) {
                    $attachment = $request->attachment;
                    $original_name = $attachment['name'];
                    $base64Decode = base64_decode($attachment['base64'], true);

                    // Generate a unique file name
                    $file_name_to_store = time() . '.' . $original_name;

                    // Save the decoded file to disk
                    Storage::disk('public')->put('company/' . $file_name_to_store, $base64Decode);

                    $size = Storage::disk('public')->size('company/' . $file_name_to_store);
                    $company->image()->delete();
                    $uploaded_file = new UploadedFile();
                    $uploaded_file->storage_name = $file_name_to_store;
                    $uploaded_file->viewable_name = $original_name;
                    $uploaded_file->storage_size = $size;
                    $uploaded_file->fileable()->associate($company);
                    $uploaded_file->save();
                }

                if (!empty($request->bankDetails)) {
                    $banks_data = [];
                    //adding bank details
                    foreach ($request->bankDetails as $detail) {
                        $banks_data[] = [
                            'bank_name' => $detail['bankName'],
                            'swift' => $detail['swift'],
                            'iban' => $detail['iban'],
                        ];
                    }

                    $company->bankDetails()->createMany($banks_data);
                }
                if (!empty($request->assignees) && $request->customerType == 'lead') {
                    foreach ($request->assignees as $assignee) {
                        $assignee_data[] = [
                            'user_id' => $assignee,
                        ];
                    }
                    $company->assignees()->createMany($assignee_data);
                }
                if (!empty($request->contactSources)) {
                    $source_ids = [];
                    foreach ($request->contactSources as $source) {
                        $source_ids[] = $source['id'];
                    }
                    $company->contactReportSources()->sync($source_ids);
                }

                if (!empty($request->priceLists)) {
                    $this->syncPriceListsToCompany($company, $request->priceLists);
                }

                $company_id = $company->id;
                $company_number = $company->company_number;

                $location = new CompanyLocation;
                $location->address_first = $request->addressFirst;
                $location->address_second = $request->addressSecond ?? null;
                $location->city = $request->city;
                $location->country_id = $request->country;
                $location->state = $request->state;
                $location->zip = $request->zip;
                $location->is_head_quarters = true;
                $location->company_id = $company_id;
                $location->save();
                $content = [
                    'moduleAction' => 'create' . $type,
                    'data' => [
                        strtolower($type) => $company?->toArray(),
                        'assignees' => $company->assignees?->toArray(),
                        'bank_details' => $company->bankDetails?->toArray(),
                        'contact_reports' => $company->contactReports?->toArray(),
                        'location' => $location?->toArray(),
                    ],
                ];
                GlobalSettingHelper::sendEloAPIRequest($content, $company);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => ($request->customerType == "lead" ? "Lead" : ($request->customerType == "partner" ? "Partner" : "Company")) . " " . "has been created",
            'company_id' => $company_id,
            'company_number' => $company_number
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/companies/{id}",
     *      operationId="getCompanySingleRecord",
     *      tags={"Company"},
     *      summary="Get single record of Company",
     *      description="Display the specified resource",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of company record",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function show($id)
    {
        return $this->customerHelper->show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Put(
     *      path="/companies/{id}",
     *      operationId="UpdateCompany",
     *      tags={"Company"},
     *      summary="Update company records",
     *      description="Update the specified resource in storage",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of company record",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Json request",
     *    @OA\JsonContent(
     *       required={"companyName","vatId", "type", "customerType", "addressFirst", "city", "country", "state",
     *                  "zip", "url", "bankDetails"},
     *       @OA\Property(property="companyName", type="string"),
     *       @OA\Property(property="vatId", type="string"),
     *      @OA\Property(property="fax", type="string"),
     *       @OA\Property(property="type", type="string"),
     *       @OA\Property(property="customerType", type="string"),
     *       @OA\Property(property="addressFirst", type="string"),
     *     @OA\Property(property="city", type="string"),
     *     @OA\Property(property="country", type="string"),
     *     @OA\Property(property="state", type="string"),
     *     @OA\Property(property="zip", type="string"),
     *     @OA\Property(property="url", type="string"),
     *       @OA\Property(property="termsOfPayment", type="string"),
     *     @OA\Property(property="invoiceEmail", type="string"),
     *     @OA\Property(property="defaultServiceProduct", type="string"),
     *     @OA\Property(property="defaultServiceHourlyRate", type="string"),
     *     @OA\Property(property="defaultServiceDailyRate", type="string"),
     *     @OA\Property(property="discount", type="string"),
     *     @OA\Property(property="phone", type="string"),
     *     @OA\Property(property="status", type="string"),
     *       @OA\Property(property="notes", type="string"),
     *      @OA\Property(property="size", type="string"),
     *      @OA\Property(property="orderProbability", type="string"),
     *      @OA\Property(property="assignees", type="object", example="[1,2]"),
     *       @OA\Property(property="bankDetails", type="array",
     *          @OA\Items(
     *     type="object",
     *     required={"bankName", "swift", "iban"},
     *                     @OA\Property(property="bankName", type="string"),
     *                     @OA\Property(property="swift", type="string"),
     *                     @OA\Property(property="iban", type="string"),
     *                 )
     *      ),
     *
     *    ),
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function update(Request $request, $id)
    {
        return $this->customerHelper->update($request, $id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *      path="/companies/{id}",
     *      operationId="DeleteCompanySingleRecord",
     *      tags={"Company"},
     *      summary="Delete single record of Company",
     *      description="Remove the specified resource from storage",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of company record",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function destroy($id)
    {
        return $this->customerHelper->destroy($id);
    }

    /**
     * Restore the previously deleted source.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $model = Company::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }

    /**
     * Export csv of all leads or companies.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\get(
     *      path="/companies/download-csv",
     *      operationId="CompaniesCsvFile",
     *      tags={"Company"},
     *      summary="export company csv file",
     *      description="Export csv of all leads or companies.",
     *      @OA\Parameter(
     *          name="type",
     *          description="",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function exportCsv(Request $request)
    {
        $file_name = '';
        if ($request->type == 'lead') {
            $file_name = 'leads.csv';
            $companies = Company::where('customer_type', 'lead')->get();
        } else if ($request->type == 'customer') {
            $file_name = 'customers.csv';
            $companies = Company::where('customer_type', 'customer')->get();
        } else if ($request->type == 'partner') {
            $file_name = 'partners.csv';
            $companies = Company::where('customer_type', 'partner')->get();
        }
        return $this->createCSV($companies, $file_name, $request->type);
    }

    /**
     * Get latest exported csv of all leads or companies.Their location, bank details and terms of payments
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\get(
     *      path="/companies/download-latest-csv",
     *      operationId="CompaniesLatestCsvFile",
     *      tags={"Company"},
     *      summary="export latest company csv file",
     *      description="Get latest exported csv of all leads or companies.Their location, bank details and terms of payments",
     *      @OA\Parameter(
     *          name="type",
     *          description="",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function exportLatestCSV(Request $request)
    {
        $file_name = $request->type . '.csv';
        $exported_csv = ExportedCsvInfo::where('exported_csv_name', $request->type)->first();
        if (empty($exported_csv)) return $this->exportCsv($request);
        $exported_csv_time = Carbon::parse($exported_csv->exported_time);
        $companies =
            Company::where('customer_type', $request->type)->where('updated_at', '>', $exported_csv_time)
            ->orWhereHas('locations', function ($query) use ($exported_csv_time) {
                // Query the company details in the locations table that have been updated after the specific time
                $query->where('updated_at', '>', $exported_csv_time);
            })
            ->orWhereHas('bankDetails', function ($query) use ($exported_csv_time) {
                // Query the company details in the locations table that have been updated after the specific time
                $query->where('updated_at', '>', $exported_csv_time);
            })
            ->orWhereHas('termsOfPayment', function ($query) use ($exported_csv_time) {
                // Query the company details in the locations table that have been updated after the specific time
                $query->where('updated_at', '>', $exported_csv_time);
            })
            ->get();

        return $this->createCSV($companies, $file_name, $request->type, true);
    }

    /**
     * returns elo configuration after modification
     * @param string $action
     * @param string $type
     * @return array
     */
    private function getEloConfiguration($action, $type)
    {
        return [
            "usedHandling" => [
                "indicatorFieldName" => "moduleAction",
                "indicatorFieldValue" => $action . $type,
                "rfName" => "RF_dsh_herorest_service_HandleSord",
                "mappings" => [
                    [
                        "jsonKey" => "companyName",
                        "key" => "COMPANY_NAME",
                        "type" => "GRP",
                        "check" => false
                    ],
                    [
                        "jsonKey" => "companyNumber",
                        "key" => "COMPANY_REFERENCE",
                        "type" => "GRP"
                    ],
                    [
                        "jsonKey" => "phone",
                        "key" => "COMPANY_TELEPHONE",
                        "type" => "GRP"
                    ],
                    [
                        "jsonKey" => "city",
                        "key" => "ADDRESS_CITY",
                        "type" => "GRP",
                        "check" => false
                    ],
                    [
                        "check" => false,
                        "jsonKey" => "zip",
                        "key" => "ADDRESS_ZIP_CODE",
                        "type" => "GRP"
                    ],
                    [
                        "jsonKey" => "state",
                        "key" => "ADDRESS_STATE",
                        "type" => "GRP"
                    ],
                    [
                        "jsonKey" => "countryName",
                        "key" => "ADDRESS_COUNTRY",
                        "type" => "GRP"
                    ],
                    [
                        "jsonKey" => "addressFirst",
                        "key" => "ADDRESS_STREET",
                        "type" => "GRP"
                    ],
                    [
                        "jsonKey" => "addressSecond",
                        "key" => "ADDRESS_ADDITION",
                        "type" => "GRP"
                    ],
                    [
                        "jsonKey" => "url",
                        "key" => "COMPANY_WEBSITE",
                        "type" => "GRP"
                    ],
                    [
                        "jsonKey" => "fax",
                        "key" => "COMPANY_FAX",
                        "type" => "GRP"
                    ],
                    [
                        "jsonKey" => "vatId",
                        "key" => "FINANCE_VAT_ID_NO",
                        "type" => "GRP"
                    ],
                    [
                        "jsonKey" => "customerType",
                        "key" => "CONTACTLIST_REFERENCE",
                        "type" => "GRP",
                        "converter" => "",
                        "check" => false
                    ],
                    [
                        "jsonKey" => "id",
                        "key" => "DSH_AP_ID",
                        "type" => "GRP",
                        "converter" => "STRING",
                        "check" => true
                    ]
                ],
                "rfEnhancements" => [
                    [
                        "propertyName" => $action,
                        "propertyValue" => true
                    ],
                    [
                        "propertyName" => "findMask",
                        "propertyValue" => "Company"
                    ],
                    [
                        "propertyName" => "workflow",
                        "propertyValue" => "sol.contact.company.update"
                    ],
                    [
                        "propertyName" => "fallbackCreate",
                        "propertyValue" => "createCustomer"
                    ]
                ],
                "staticMappings" => []
            ],
            "returnMessage" => "no metadata found!",
            "setInfo" => []
        ];
    }

    /**
     * Create csv of selected companies.
     *
     * @param string $type which is company type
     * @param string $file_name which is name we want to create the file
     * @param object $companies which is list of selected companies
     * @param boolean $is_latest_exported_csv
     * @return \Illuminate\Http\Response
     */
    private function createCSV($companies, $file_name, $type, $is_latest_exported_csv = false)
    {
        $columns = [
            'Customer Number', 'Customer Name', 'Type', 'URL', 'VAT',
            'Phone', 'Customer Type', 'Address', 'State', 'Country', 'City', 'Zip', 'Fax'
        ];
        $callback = function () use ($companies, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($companies as $company) {
                $row['Customer Number'] = $company->company_number;
                $row['Customer Name'] = $company->company_name;
                $row['Type'] = $company->type;
                $row['URL'] = $company->url;
                $row['VAT'] = $company->vat_id;
                $row['Phone'] = $company->phone;
                $row['Customer Type'] = $company->customer_type;
                $row['Address'] = $company->headQuarters->address_first ?? '';
                $row['State'] = $company->headQuarters->state ?? '';
                $row['Country'] = $company->headQuarters->country ?? '';
                $row['City'] = $company->headQuarters->city ?? '';
                $row['Zip'] = $company->headQuarters->zip ?? '';
                $row['Fax'] = $company->fax ?? '';

                fputcsv($file, [
                    $row['Customer Number'], $row['Customer Name'],
                    $row['Type'], $row['URL'], $row['VAT'], $row['Phone'], $row['Customer Type'],
                    $row['Address'], $row['State'], $row['Country'], $row['City'], $row['Zip'], $row['Fax']
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
     * Sync the given price lists to a company.
     *
     * @param Company $company The company to sync price lists to.
     * @param array $price_lists An array of price lists to sync.
     * @return Company The updated company instance.
     */
    private function syncPriceListsToCompany(Company $company, array $price_lists)
    {
        $ids = [];
        foreach ($price_lists as $price_list) {
            $ids[] = $price_list['id'];
        }
        $company->priceLists()->sync($ids);
        return $company;
    }


    public function partnerCompanies(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new Company;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $companies = $model->where('partner_id', $request->id)->paginate($per_page)->withQueryString()
            ->through(fn ($item) => [
                'id' => $item->id,
                'companyName' => $item->company_name,
                'vatId' => $item->vat_id,
                'url' => $item->url,
                'type' => $item->type,
                'customerType' => $item->customer_type,
                'companyNumber' => $item->company_number,
                'applyReverseChange' => $item->apply_reverse_change,
                'state' => "",
                'city' => "",
                'country' => "",
                'address' => "",
                'notes' => $item->notes,
                'status' => $item->status,
                'expiryDate' => $item->expiry_dt ? Carbon::parse($item->expiry_dt)->toDateString() : '',
                'termsOfPayment' => $item->terms_of_payment,
                'contactReportSources' => $item?->contactReportSources,
                'externalOrderNumber' => $item?->external_order_number
            ]);

        return response()->json(['data' => $companies]);
    }
}
