<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Http\Resources\ModuleHistoryResource;
use App\Models\ExportedCsvInfo;
use App\Models\GlobalSetting;
use App\Models\Supplier;
use App\Models\SupplierLocation;
use App\Models\UploadedFile;
use App\Traits\CustomHelper;
use App\Utils\Logger;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    use CustomHelper;
    /**
     * Run on instantiate
     */
    public function __construct()
    {
        $this->middleware('check.permission:supplier.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:supplier.create', ['only' => ['store']]);
        $this->middleware('check.permission:supplier.edit', ['only' => ['update']]);
        $this->middleware('check.permission:supplier.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage;

        $model = new Supplier();
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $models = $model->filter(staticRequest::only('search'))->paginate($per_page);
        $paginate_data = $models->toArray();

        $model_collect = $models->map(function ($item) {
            return [
                'id' => $item->id,
                'supplierName' => $item->supplier_name,
                'vatId' => $item->vat_id,
                'url' => $item->url,
                'type' => $item->type,
                'supplierType' => $item->supplier_type,
                'supplierNumber' => $item->supplier_number
            ];
        });
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "supplierName" => "required|string",
            "vatId" => "required|string",
            "url" => "required|string",
            "phone" => "required|string",
            "fax" => "nullable|string",
            "addressFirst" => "required",
            "city" => "required",
            "country" => "nullable",
            "state" => "required",
            "zip" => "required",
            // "termsOfPayment" => "required", uncomment when seeders are updated
            "bankDetails" => "nullable|array",
            "bankDetails.*.bankName" => "required",
            "bankDetails.*.swift" => "required|string",
            "bankDetails.*.iban" => "required|string"

        ], [
            "bankDetails.*.bankName.required" => "The bank name field is required.",
            "bankDetails.*.swift.required" => "The swift field is required.",
            "bankDetails.*.iban.required" => "The iban field is required."
        ]);

        $supplier_id = null;
        try {
            DB::transaction(function () use ($request, &$supplier_id) {
                //Create Supplier
                $supplier = new Supplier;
                $supplier->supplier_name = $request->supplierName;
                $supplier->vat_id = $request->vatId;
                $supplier->url = $request->url;
                $supplier->phone = $request->phone;
                $supplier->fax = $request->fax;
                $supplier->terms_of_payment = $request->termsOfPayment ?? null;
                $global_invoice_setting = GlobalSetting::where('key', 'supplier')->first();
                if (empty($global_invoice_setting)) {
                    $global_setting = new GlobalSetting;
                    $global_setting->key = 'supplier';
                    $global_setting->value = 700001;
                    $global_setting->save();
                } else {
                    $global_setting = tap(DB::table('global_settings')->where('key', 'supplier'))->update([
                        'value' => DB::raw("value+1")
                    ])->first();
                }
                $supplier->supplier_number = $global_setting->value;
                $supplier->save();

                if ($request->attachment) {
                    $attachment = $request->attachment;
                    $original_name = $attachment['name'];
                    $base64Decode = base64_decode($attachment['base64'], true);

                    // Generate a unique file name
                    $file_name_to_store = time() . '.' . $original_name;

                    // Save the decoded file to disk
                    Storage::disk('public')->put('supplier/' . $file_name_to_store, $base64Decode);

                    $size = Storage::disk('public')->size('supplier/' . $file_name_to_store);
                    $supplier->image()->delete();
                    $uploaded_file = new UploadedFile();
                    $uploaded_file->storage_name = $file_name_to_store;
                    $uploaded_file->viewable_name = $original_name;
                    $uploaded_file->storage_size = $size;
                    $uploaded_file->fileable()->associate($supplier);
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

                    $supplier->bankDetails()->createMany($banks_data);
                }

                //** */
                $supplier_id = $supplier->id;
                $location = new SupplierLocation;
                $location->address_first = $request->addressFirst;
                $location->address_second = $request->addressSecond ?? null;
                $location->city = $request->city;
                $location->country = $request->country;
                $location->state = $request->state;
                $location->zip = $request->zip;
                $location->is_head_quarters = true;
                $location->supplier_id = $supplier_id;
                $location->save();
                $content = [
                    'moduleAction' => 'createSupplier',
                    'data' => [
                        'supplier' => $supplier?->toArray(),
                        'location' => $location?->toArray(),
                    ],
                ];
                GlobalSettingHelper::sendEloAPIRequest($content, $supplier);
                //                Logger::auditLog([
                //                    'entity' => 'Supplier',
                //                    'event' => 'create',
                //                    'ip' => $request->ip(),
                //                    'userId' => $request->get('auth_user_id'),
                //                    'new' => [
                //                        'supplier' => @$supplier,
                //                        'companyLocation' => @$location,
                //                    ],
                //                ]);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => trans('messages.record_saved_success', ['name' => 'Supplier']),
            'supplier_id' => $supplier_id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Supplier::where('id', $id)->first();
        if ($model->image) {
            $storageFile = Storage::disk('public')->get('supplier/' . $model->image->storage_name);
            $mime = Storage::disk('public')->mimeType('supplier/' . $model->image->storage_name);
        }
        return response()->json([
            'modelData' => [
                'id' => $model->id,
                'supplierName' => $model->supplier_name,
                'supplierNumber' => $model->supplier_number,
                'vatId' => $model->vat_id,
                'url' => $model->url,
                'phone' => $model->phone,
                'fax' => $model->fax,
                'termsOfPayment' => $model->terms_of_payment ?? null,
                'image' => $model->image ? [
                    'viewableName' => $model->image->viewable_name,
                    'url' => "data:$mime;base64," . base64_encode($storageFile),
                ] : [],
                'bankDetails' => isset($model->bankDetails) ? $model->bankDetails->map(function ($bank_detail) {
                    return [
                        'bankName' => $bank_detail->bank_name,
                        'swift' => $bank_detail->swift,
                        'iban' => $bank_detail->iban,
                    ];
                }) : [],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "supplierName" => "required|string",
            "vatId" => "required|string",
            "url" => "required|string",
            "phone" => "required|string",
            "fax" => "nullable|string",
            "addressFirst" => "required",
            "city" => "required",
            "country" => "nullable",
            "state" => "required",
            "zip" => "required",
            "termsOfPayment" => "nullable",
            "bankDetails" => "nullable|array",
            "bankDetails.*.bankName" => "required",
            "bankDetails.*.swift" => "required|string",
            "bankDetails.*.iban" => "required|string"

        ], [
            "bankDetails.*.bankName.required" => "The bank name field is required.",
            "bankDetails.*.swift.required" => "The swift field is required.",
            "bankDetails.*.iban.required" => "The iban field is required."
        ]);
        $supplier = Supplier::findOrFail($id);
        try {
            DB::transaction(function () use ($id, $request, &$supplier) {
                //Update Supplier
                $supplier->supplier_name = $request->supplierName;
                $supplier->vat_id = $request->vatId;
                $supplier->url = $request->url;
                $supplier->phone = $request->phone;
                $supplier->fax = $request->fax;
                $supplier->terms_of_payment = $request->termsOfPayment ?? null;
                $supplier->save();

                //added Attachment for  supplier logo
                if ($request->attachment) {
                    $attachment = $request->attachment;
                    $original_name = $attachment['name'];
                    $base64Decode = base64_decode($attachment['base64'], true);

                    // Generate a unique file name
                    $file_name_to_store = time() . '.' . $original_name;

                    // Save the decoded file to disk
                    Storage::disk('public')->put('supplier/' . $file_name_to_store, $base64Decode);

                    $size = Storage::disk('public')->size('supplier/' . $file_name_to_store);
                    $supplier->image()->delete();
                    $uploaded_file = new UploadedFile();
                    $uploaded_file->storage_name = $file_name_to_store;
                    $uploaded_file->viewable_name = $original_name;
                    $uploaded_file->storage_size = $size;
                    $uploaded_file->fileable()->associate($supplier);
                    $uploaded_file->save();
                }

                //updating bank details
                if (isset($supplier->bankDetails)) {
                    $supplier->bankDetails()->delete();
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

                    $supplier->bankDetails()->createMany($banks_data);
                }

                /** */
                $location = SupplierLocation::where("id", $request->location_id)->first();
                $location->address_first = $request->addressFirst;
                $location->address_second = $request->addressSecond ?? null;
                $location->city = $request->city;
                $location->country = $request->country;
                $location->state = $request->state;
                $location->zip = $request->zip;
                $location->is_head_quarters = true;
                $location->supplier_id = $id;
                $location->save();
                $content = [
                    'moduleAction' => 'updateSupplier',
                    'data' => [
                        'supplier' => $supplier?->toArray(),
                        'location' => $location?->toArray(),
                    ],
                ];
                GlobalSettingHelper::sendEloAPIRequest($content, $supplier);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => "Supplier has been updated",
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Supplier::find($id);
        $content = [
            'moduleAction' => 'deleteSupplier',
            'data' => [
                'supplier' => $model?->toArray(),
            ],
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        $model->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    /**
     * Restore the previously deleted source.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $model = Supplier::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }

    //function to export latest changed CSV
    public function exportLatestCSV(Request $request)
    {
        $file_name = 'suppliers.csv';
        $exported_csv = ExportedCsvInfo::where('exported_csv_name', 'supplier')->first();
        if (empty($exported_csv)) return $this->exportCsv();
        $exported_csv_time = Carbon::parse($exported_csv->exported_time);
        $suppliers = Supplier::where('updated_at', '>', $exported_csv_time)->orWhereHas('locations', function ($query) use ($exported_csv_time) {
            // Query the company details in the locations table that have been updated after the specific time
            $query->where('updated_at', '>', $exported_csv_time);
        })->get();
        return $this->createCsv($suppliers, $file_name, true);
    }
    //function to export supplier csv
    public function exportCsv()
    {
        $file_name = 'suppliers.csv';
        $suppliers = Supplier::all();
        return $this->createCsv($suppliers, $file_name);
    }

    /**
     * Create csv file for supplier
     * @param  object $suppliers
     * @param  string $file_name
     * @param  boolean $is_latest_exported
     * @return \Illuminate\Http\Response
     */
    private function createCsv($suppliers, $file_name, $is_latest_exported = false)
    {
        $columns = [
            'Supplier Number', 'Supplier Name', 'URL', 'VAT',
            'Phone', 'Address', 'State', 'Country', 'City', 'Zip', 'Fax',
            'Bank Account Name', 'IBAN', 'BIC'
        ];
        $callback = function () use ($suppliers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($suppliers as $supplier) {
                $row['Supplier Number']  = $supplier->supplier_number;
                $row['Supplier Name']    = $supplier->supplier_name;
                $row['URL']  = $supplier->url;
                $row['VAT']  = $supplier->vat_id;
                $row['Phone']  = $supplier->phone;
                $row['Address'] =  $supplier->headQuarters->address_first ?? '';
                $row['State'] =  $supplier->headQuarters->state ?? '';
                $row['Country'] =  $supplier->headQuarters->country ?? '';
                $row['City'] =  $supplier->headQuarters->city ?? '';
                $row['Zip'] =  $supplier->headQuarters->zip ?? '';
                $row['Fax'] =  $supplier->fax ?? '';
                $bank_details = [];
                if (!empty($supplier->bankDetails)) {
                    foreach ($supplier->bankDetails as $detail) {
                        $bank_details[] = [
                            $row['Supplier Number'], $row['Supplier Name'],  $row['URL'], $row['VAT'], $row['Phone'],
                            $row['Address'], $row['State'], $row['Country'], $row['City'], $row['Zip'], $row['Fax'],
                            $detail->bank_name, $detail->iban, $detail->swift
                        ];
                    }
                }
                if (empty($bank_details)) {
                    fputcsv($file, [
                        $row['Supplier Number'], $row['Supplier Name'],  $row['URL'], $row['VAT'], $row['Phone'],
                        $row['Address'], $row['State'], $row['Country'], $row['City'], $row['Zip'], $row['Fax']
                    ]);
                } else {
                    foreach ($bank_details as $bank_detail) {
                        fputcsv($file, $bank_detail);
                    }
                }
            }
            fclose($file);
        };
        if (!$is_latest_exported) {
            $exported_csv = ExportedCsvInfo::firstOrNew(['exported_csv_name' => 'supplier']);
            $exported_csv->exported_time = Carbon::now();
            $exported_csv->save();
        }
        return response()->streamDownload($callback, $file_name, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$file_name",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }
}
