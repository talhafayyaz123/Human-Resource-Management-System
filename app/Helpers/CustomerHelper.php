<?php

namespace App\Helpers;

use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\CompanyUsers;
use App\Models\UploadedFile;
use App\Traits\CustomHelper;
use App\Traits\SetGlobalNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as staticRequest;
use Illuminate\Support\Facades\Storage;
use Exception;

class CustomerHelper
{
    use SetGlobalNumber, CustomHelper;

    public function index($request, $tenantId = null)
    {
        $per_page = $request->perPage ?? 25;
        $model = new Company;
        if ($request->has('isSystem')) {
            $model = $model->orderBy('customer_type');
        } else if (gettype($request->customerType) == "array") {
            $model = $model->whereIn("customer_type", $request->customerType);
        } else if ($request->customerType == 'lead' || $request->customerType == 'partner') {
            $model = $model->where("customer_type", $request->customerType);
        } else {
            $model = $model->where("customer_type", "customer");
        }

        if ($request->partnerId) {
            $model = $model->where('partner_id', $request->partnerId);
        }

        if ($tenantId) {
            $model = $model->where(function ($query) use ($tenantId) {
                $query->where('partner_id', "!=", $tenantId)->orWhereNull('partner_id');
            });
        }

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        //$models = $model->filter(staticRequest::only('search', 'status'))->paginate($per_page);
        $models = $model->filter(staticRequest::only('search', 'leadStatus'))->paginate($per_page);
        $model_collect = $models;
        if ($request->has('selectedIds')) {
            $model_collect = $this->getSelectedIds($model, $model_collect, $request->selectedIds);
        }

        $model_collect = $model_collect->map(function ($item) {
            $ret = [
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
                'externalOrderNumber' => $item?->external_order_number,
                'partnerName' => $item->partner?->company_name,
            ];

            if (isset($item->headQuarters)) {
                $ret['state'] = $item->headQuarters->state;
                $ret['city'] = $item->headQuarters->city;
                $ret['country'] = $item->headQuarters->country;
                $ret['address'] = $item->headQuarters->address_first;
                $ret['code'] = $item->headQuarters->zip;
            }

            return $ret;
        })->unique('id')->values();
        return response()->json([
            'data' => $model_collect,
            'links' => $models->links(),
            'current_page' => $models->currentPage(),
            'from' => $models->firstItem(),
            'last_page' => $models->lastPage(),
            'path' => request()->url(),
            'per_page' => $models->perPage(),
            'to' => $models->lastItem(),
            'total' => $models->total(),
        ], 200);
    }

    public function show($id)
    {
        $model = Company::where('id', $id)->first();
        if (!empty($model)) {
            if ($model->image) {
                $storageFile = Storage::disk('public')->get('company/' . $model->image->storage_name);
                $mime = Storage::disk('public')->mimeType('company/' . $model->image->storage_name);
            }
            return response()->json([
                'modelData' => [
                    'id' => $model->id,
                    'companyName' => $model->company_name,
                    'companyNumber' => $model->company_number,
                    'vatId' => $model->vat_id,
                    'url' => $model->url,
                    'phone' => $model->phone,
                    'fax' => $model->fax,
                    'status' => $model->status,
                    'leadStatusId' => $model->lead_status_id,
                    'type' => $model->type,
                    'partnerType' => $model->partner_type,
                    'customerType' => $model->customer_type,
                    'termsOfPayment' => $model->terms_of_payment,
                    'termsOfPaymentName' => $model->termsOfPayment?->name,
                    'invoiceEmail' => $model->invoice_email,
                    'defaultServiceProduct' => $model->default_service_product,
                    'defaultServiceHourlyRate' => $model->default_service_hourly_rate,
                    'defaultServiceDailyRate' => $model->default_service_daily_rate,
                    'mergePdfsOnDefault' => $model->merge_pdfs_on_default,
                    'applyReverseChange' => $model->apply_reverse_change,
                    'isCustomerPortalOnboarding' => $model->is_customer_portal_onboarding,
                    'discount' => $model->discount,
                    'expiryDate' => $model->expiry_dt ? Carbon::parse($model->expiry_dt)->toDateString() : '',
                    'notes' => $model->notes,
                    'size' => $model->size,
                    'orderProbability' => $model->order_probability,
                    'contactSources' => $model->contactReportSources,
                    'externalOrderNumber' => $model->external_order_number,
                    'defaultPaymentPeriod' => $model->defaultPaymentPeriod ? [
                        'id' => $model->defaultPaymentPeriod->id,
                        'name' => $model->defaultPaymentPeriod->name
                    ] : null,
                    'bankDetails' => isset($model->bankDetails) ? $model->bankDetails->map(function ($bank_detail) {
                        return [
                            'bankName' => $bank_detail->bank_name,
                            'swift' => $bank_detail->swift,
                            'iban' => $bank_detail->iban,
                        ];
                    }) : [],
                    'priceLists' => isset($model->priceLists) ? $model->priceLists->map(function ($price) {
                        return [
                            'id' => $price->id,
                            'name' => $price->name,
                            'isDefault' => $price->is_default,
                            'status' => $price->status,
                            'productSoftwareName' => $price->productSoftware?->name,
                            'productSoftwareId' => $price->product_software_id,
                        ];
                    }) : [],
                    'assignees' => isset($model->assignees) ? $model->assignees->map(function ($assignee) {
                        return [
                            'id' => $assignee->user_id
                        ];
                    }) : [],
                    'state' => $model->headQuarters?->state,
                    'city' => $model->headQuarters?->city,
                    'country' => $model->headQuarters?->country,
                    'address' => $model->headQuarters?->address_first,
                    'code' => $model->headQuarters?->zip,
                    'image' => $model->image ? [
                        'viewableName' => $model->image->viewable_name,
                        'url' => "data:$mime;base64," . base64_encode($storageFile),
                    ] : [],
                    'partner' => $model->partner ? [
                        'id' => $model->partner->id,
                        'companyName' => $model->partner->company_name,
                    ] : null,
                    'isEloPartner' => $model->is_elo_partner ? true : false
                ],
            ]);
        }
        return response()->json([
            'modelData' => []
        ]);
    }


    public function update($request, $id)
    {
        $request->validate([
            "companyName" => "required|string",
            "fax" => "nullable|string",
            "customerType" => "required",
            "location_id" => "required",
            "addressFirst" => "required",
            "city" => "required",
            "country" => "required",
            "zip" => "required",
            "url" => "required",
            //            'defaultPaymentPeriod' => 'required_if:customerType,==,customer',
            "termsOfPayment" => "nullable",
            "assignees" => ['nullable', 'array'],
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
        $company = Company::where("id", $id)->first();
        if ($request->customerType == "customer" && $company->customer_type != 'lead') {
            $request->validate([
                "status" => "required|string",
            ]);
        } else if ($request->customerType == "lead") {
            $request->validate([
                "statusId" => "required|integer|exists:App\Models\LeadStatus,id",
                "vatId" => "required|string",
            ]);
        } else if ($request->customerType == "partner") {
            $request->validate([
                "partnerType" => "required|in:reseller_partner,business_partner",
                'isEloPartner' => 'nullable|boolean'
            ]);
        }


        try {
            DB::transaction(function () use ($id, $request, &$company) {
                if (!empty($request->priceLists)) {
                    $price_list_collection = collect($request->priceLists);

                    $duplicateCollection = $price_list_collection->duplicates('productSoftwareId');

                    if ($duplicateCollection->isNotEmpty()) {
                        throw new Exception("Multiple software records exist with software id in price list");
                    }
                }
                //Update Company
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
                $company->discount = $request->discount;
                $company->default_payment_period = $request->defaultPaymentPeriod ?? null;
                $company->apply_reverse_change = $request->applyReverseChange ?? 0;
                $company->merge_pdfs_on_default = $request->mergePdfsOnDefault;
                $company->external_order_number = $request->externalOrderNumber ?? 0;
                $company->is_customer_portal_onboarding = $request->isCustomerPortalOnboarding ?? 0;
                $company->partner_id = $request->partner;
                $type = 'Customer';
                if ($company->customer_type == 'lead') {
                    $type = 'Lead';
                    $company->expiry_dt = $request->expiryDate ? Carbon::parse($request->expiryDate) : null;
                    $company->lead_status_id = $request->statusId;
                    $company->notes = $request->notes;
                    $company->size = $request->size;
                    $company->order_probability = $request->orderProbability;
                }
                if ($company->customer_type == 'customer') {
                    $company->status = $request->status;
                }
                if ($company->customer_type == 'partner') {
                    $company->partner_type = $request->partnerType;
                    $company->is_elo_partner = $request->isEloPartner ?? false;
                }
                if ($company->isDirty('customer_type')) {
                    if ($company->customer_type == 'lead') {
                        $company->company_number = $this->globalNumber($company, 'lead', 'L', '100000');
                        if (isset($company->saleOffers)) {
                            $company->saleOffers()->update(['receiver_type' => 'lead']);
                        }
                    } else {
                        $company->company_number = 'C' . $this->globalNumber($company, 'company', 'C', '100000', 'customer');
                        if (isset($company->saleOffers)) {
                            $company->saleOffers()->update(['receiver_type' => 'customer']);
                        }
                    }
                }
                $company->save();
                //from customer portal
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


                if (!empty($request->assignees)) {
                    $new_assignee_ids = collect($request->assignees)->pluck('id')->toArray();

                    $old_assignee_ids = [];
                    if (!empty($company->assignees)) {
                        $old_assignee_ids = $company->assignees->pluck('user_id')->toArray();
                    }
                    // Find assignees to be added
                    $assignee_ids_to_add = array_diff($new_assignee_ids, $old_assignee_ids);
                    $assignees_to_add = [];
                    foreach ($assignee_ids_to_add as $assignee_id) {
                        $assignees_to_add[] = new CompanyUsers(['user_id' => $assignee_id]);
                    }

                    // Find assignees to be deleted
                    $assignee_ids_to_delete = array_diff($old_assignee_ids, $new_assignee_ids);

                    // Delete assignees
                    $company->assignees()->whereIn('user_id', $assignee_ids_to_delete)->delete();

                    // Add assignees
                    $company->assignees()->saveMany($assignees_to_add);
                } else {
                    if (!empty($company->assignees)) {
                        $company->assignees()->delete();
                    }
                }
                //updating bank details
                if (isset($company->bankDetails)) {
                    $company->bankDetails()->delete();
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
                if (!empty($request->priceLists)) {
                    $this->syncPriceListsToCompany($company, $request->priceLists);
                }
                if (!empty($request->contactSources)) {
                    $source_ids = [];
                    foreach ($request->contactSources as $source) {
                        $source_ids[] = $source['id'];
                    }
                    $company->contactReportSources()->sync($source_ids);
                    if (isset($company->contactReports)) {
                        foreach ($company->contactReports as $report) {
                            $report->contactReportSources()->sync($source_ids);
                        }
                    }
                } else if ($company->contactReportSources) {
                    if (isset($company->contactReports)) {
                        foreach ($company->contactReports as $report) {
                            $report->contactReportSources()->detach();
                        }
                    }
                    $company->contactReportSources()->detach();
                }
                $location = CompanyLocation::where("id", $request->location_id)->first();
                $location->address_first = $request->addressFirst;
                $location->address_second = $request->addressSecond ?? null;
                $location->city = $request->city;
                $location->country_id = $request->country;
                $location->state = $request->state;
                $location->zip = $request->zip;
                $location->is_head_quarters = true;
                $location->company_id = $id;
                $location->save();
                $content = [
                    'moduleAction' => 'update' . $type,
                    'data' => [
                        strtolower($type) => $company?->toArray(),
                        'assignees' => $company->assignees?->toArray(),
                        'bank_details' => $company->bankDetails?->toArray(),
                        'contact_reports' => $company->contactReports?->toArray(),
                        'location' => $location?->toArray(),
                    ],
                ];
                $elo = $this->getEloConfiguration('update', $type);
                GlobalSettingHelper::sendEloAPIRequest($content, $company, $elo);
            });
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                422
            );
        }
        return response()->json([
            'success' => true,
            'message' => ($request->customerType == "lead" ? "Lead" : ($request->customerType == "partner" ? "Partner" : "Company")) . " " . "has been updated",
        ]);
    }


    public function destroy($id)
    {
        $model = Company::find($id);
        $type = 'Customer';
        if ($model->customer_type == 'lead') {
            $type = 'Lead';
        }
        $content = [
            'moduleAction' => 'delete' . $type,
            'data' => [
                strtolower($type) => $model?->toArray(),
                'assignees' => $model->assignees?->toArray(),
                'bank_details' => $model->bankDetails?->toArray(),
                'contact_reports' => $model->contactReports?->toArray(),
                'location' => $model?->toArray(),
            ],
        ];
        $elo = $this->getEloConfiguration('delete', $type);
        GlobalSettingHelper::sendEloAPIRequest($content, null, $elo);
        $model->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
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
}
