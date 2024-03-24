<?php

namespace App\Http\Controllers;

use App\Dto\CreateTaskDto;
use App\Helpers\GlobalSettingHelper;
use App\Models\ContactReport;
use App\Models\GlobalSetting;
use App\Utils\Logger;
use Carbon\Carbon;
use App\Models\ContactReportCompanyEmployee;
use App\Models\ContactReportUser;
use App\Models\Task;
use App\Models\UploadedFile;
use Facades\App\Services\TaskService\TaskService;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as staticRequest;
use Illuminate\Support\Facades\Storage;

class ContactReportController extends Controller
{
    use CustomHelper;

    /**
     * Runs when instance is initiated
     */
    public function __construct()
    {
        $this->middleware('check.permission:contact-report.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:contact-report.create', ['only' => ['store']]);
        $this->middleware('check.permission:contact-report.edit', ['only' => ['update']]);
        $this->middleware('check.permission:contact-report.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/contact-reports",
     *      operationId="getContactReportList",
     *      tags={"Contact Reports"},
     *      summary="Get list of contact reports",
     *      description="Display a listing of the resource",
     *      @OA\Parameter(
     *          name="per_page",
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
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new ContactReport();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->filter(staticRequest::only('search', 'type', 'company', 'categoryId', 'contactSources'))->orderBy('contact_reports.created_at');
        $models = $model->paginate($per_page);

        $model_collect = $models->map(function ($item) {
            return [
                'id' => $item->id,
                'reportNumber' => $item->report_number,
                'subject' => $item->subject,
                'text' => $item->text,
                'type' => $item->type,
                'category' => $item->reportCategory?->name,
                'priority' => $item->priority,
                'contactType' => $item->contact_type,
                'initiatedBy' => $item->initiated_by,
                'resubmitOn' => $item->resubmit_on,
                'company' => $item->company?->company_name,
                'contactReportSources' => $item->contactReportSources
            ];
        });
        return response()->json([
            'data' => $model_collect,
            'links' => $models->links(),
            'meta' => [
                'current_page' => $models->currentPage(),
                'from' => $models->firstItem(),
                'last_page' => $models->lastPage(),
                'path' => request()->url(),
                'per_page' => $models->perPage(),
                'to' => $models->lastItem(),
                'total' => $models->total(),
            ],
        ], 200);
    }

    /**
     * Display a listing of the resource filtered by resubmit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/contact-reports/get-by-resubmit",
     *      operationId="getContactReportByResumbit",
     *      tags={"Contact Reports"},
     *      summary="Get list of contact reports",
     *      description="Display a listing of the resource",
     *      @OA\Parameter(
     *          name="per_page",
     *          description="Get records in one page default is 10",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
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
    public function getByResubmit(Request $request)
    {
        $per_page = $request->perPage ?? 10;

        $model = ContactReport::filter(staticRequest::only('search', 'type', 'company'))->orderBy('created_at');
        $model->whereNotNull('resubmit_on');
        $models = $model->paginate($per_page);
        $paginate_data = $models->toArray();

        $model_collect = $models->map(function ($item) {
            return [
                'id' => $item->id,
                'subject' => $item->subject,
                'text' => $item->text,
                'type' => $item->type,
                'priority' => $item->priority,
                'contactType' => $item->contact_type,
                'initiatedBy' => $item->initiated_by,
                'resubmitOn' => $item->resubmit_on,
                'company' => $item->company->company_name
            ];
        });
        return response()->json([
            'data' => $model_collect,
            'links' => $paginate_data['links'],
            'count' => $paginate_data['total'],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     *      path="/contact-reports",
     *      operationId="storeContactReport",
     *      tags={"Contact Reports"},
     *      summary="Store contact reports",
     *      description="Store a newly created resource in storage",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Json request",
     *    @OA\JsonContent(
     *       required={"subject","text", "type", "priority", "contactType", "initiatedBy", "companyId", "createdByEmployee",
     *                  "categoryId", "employees", "companyEmployees", "assignee", "contactSources"},
     *       @OA\Property(property="subject", type="string"),
     *       @OA\Property(property="text", type="string"),
     *      @OA\Property(property="type", type="string", example="customer/lead"),
     *       @OA\Property(property="priority", type="string"),
     *     @OA\Property(property="contactType", type="string"),
     *     @OA\Property(property="initiatedBy", type="string", example="customer/docshero"),
     *     @OA\Property(property="companyId", type="string"),
     *     @OA\Property(property="createdByEmployee", type="string"),
     *     @OA\Property(property="categoryId", type="int"),
     *     @OA\Property(property="employees", type="object", example="[1,3]"),
     *     @OA\Property(property="companyEmployees", type="object", example="[1,3]"),
     *      @OA\Property(property="assignee", type="int"),
     *      @OA\Property(property="contactSources", type="array",
     *          @OA\Items(
     *     type="object",
     *     required={"id"},
     *                     @OA\Property(property="id", type="int", example=""),
     *                 )
     *
     *      ),
     *      @OA\Property(property="uploadedFiles", type="object", example="[]"),
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
            'subject' => 'required',
            'text' => 'required',
            'type' => 'required',
            'priority' => 'required',
            'contactType' => 'required',
            'initiatedBy' => 'required',
            'companyId' => 'required',
            'createdByEmployee' => 'required',
            'categoryId' => 'required',
            'employees' => 'required',
            'companyEmployees' => 'required',
            'assignee' => 'required',
            'contactSources' => 'required'
        ]);
        //Create Contact Report
        $model = new ContactReport();
        $global_invoice_setting = GlobalSetting::where('key', 'report')->first();
        if (empty($global_invoice_setting)) {
            $global_setting = new GlobalSetting();
            $global_setting->key = 'report';
            $global_setting->value = 100;
            $global_setting->save();
        } else {
            $global_setting = tap(DB::table('global_settings')->where('key', 'report'))->update([
                'value' => DB::raw('value+1')
            ])->first();
        }
        $model->report_number = 'KB-' . $global_setting->value;
        $saveData = $this->saveData($model, $request, true);
        $content = [
            'moduleAction' => 'createContactReport',
            'data' => $saveData->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $model);
        return response()->json(['message' => 'Record created successfully.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/contact-reports/{id}",
     *      operationId="GetSingleContactReport",
     *      tags={"Contact Reports"},
     *      summary="Get single contact reports",
     *      description="Display the specified resource",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of contacnt report record entry",
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
        $model = ContactReport::where('id', $id)->first();
        $company_employees = ContactReportCompanyEmployee::where('report_id', $id)->get();
        $employees = ContactReportUser::where('report_id', $id)->get();
        return response()->json([
            'modelData' => [
                'id' => $model->id,
                'subject' => $model->subject,
                'text' => $model->text,
                'type' => $model->type,
                'priority' => $model->priority,
                'contactType' => $model->contact_type,
                'initiatedBy' => $model->initiated_by,
                'companyId' => $model->company_id,
                'categoryId' => $model->category_id,
                'resubmitOn' => $model->resubmit_on ? Carbon::parse($model->resubmit_on) : null,
                'createdByEmployee' => $model->created_by_employee,
                'companyEmployees' => !empty($company_employees) ? $company_employees->pluck('user_id') : [],
                'employees' => !empty($employees) ? $employees->pluck('user_id') : [],
                'assignee' => $model->assignee_id ?? '',
                'contactSources' => $model->contactReportSources,
                'uploadedFiles' => $model->files ?? ''
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Put(
     *      path="/contact-reports/{id}",
     *      operationId="updateContactReport",
     *      tags={"Contact Reports"},
     *      summary="update contact reports",
     *      description="Update the specified resource in storage",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of contacnt report record entry",
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
     *       required={"subject","text", "type", "priority", "contactType", "initiatedBy", "companyId", "createdByEmployee",
     *                  "categoryId", "employees", "companyEmployees", "assignee", "contactSources"},
     *       @OA\Property(property="subject", type="string"),
     *       @OA\Property(property="text", type="string"),
     *      @OA\Property(property="type", type="string", example="customer/lead"),
     *       @OA\Property(property="priority", type="string"),
     *     @OA\Property(property="contactType", type="string"),
     *     @OA\Property(property="initiatedBy", type="string", example="customer/docshero"),
     *     @OA\Property(property="companyId", type="string"),
     *     @OA\Property(property="createdByEmployee", type="string"),
     *     @OA\Property(property="categoryId", type="int"),
     *     @OA\Property(property="employees", type="object", example="[1,3]"),
     *     @OA\Property(property="companyEmployees", type="object", example="[1,3]"),
     *      @OA\Property(property="assignee", type="int"),
     *      @OA\Property(property="contactSources", type="array",
     *          @OA\Items(
     *     type="object",
     *     required={"id"},
     *                     @OA\Property(property="id", type="int", example=""),
     *                 )
     *
     *      ),
     *      @OA\Property(property="uploadedFiles", type="object", example="[]"),
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
        $request->validate([
            'subject' => 'required',
            'text' => 'required',
            'type' => 'required',
            'priority' => 'required',
            'contactType' => 'required',
            'initiatedBy' => 'required',
            'companyId' => 'required',
            'createdByEmployee' => 'required',
            'categoryId' => 'required',
            'employees' => 'required',
            'companyEmployees' => 'required',
            'assignee' => 'required'
        ]);

        //Create Contact Report
        $model = ContactReport::where('id', $id)->first();
        $saveData = $this->saveData($model, $request, false);
        $content = [
            'moduleAction' => 'updateContactReport',
            'data' => $saveData->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $model);
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }

    /**
     * Saves the data based on provided resource item
     *
     * @param   object  $model
     * @param   object  $request
     * @param   boolean $is_new
     * @param   array   Response
     */
    public function saveData($model, $request, $is_new = false)
    {
        $model->subject = $request->subject;
        $model->text = $request->text;
        $model->type = $request->type;
        $model->priority = $request->priority;
        $model->contact_type = $request->contactType;
        $model->initiated_by = $request->initiatedBy;
        $model->created_by_employee = $request->createdByEmployee;
        $model->company_id = $request->companyId;
        $model->category_id = $request->categoryId;
        $model->assignee_id = $request->assignee;
        if ($request->resubmitOn != null) {
            $model->resubmit_on = Carbon::parse($request->resubmitOn);
        }
        $model->save();

        if (isset($request->resubmitOn) && $is_new) {
            $company_name = '[' . $model->company?->company_name . ']';
            $task_dto = new CreateTaskDto($company_name . ' ' . $request->subject, $request->assignee, 'new', $request->priority, $request->text, Carbon::parse($request->resubmitOn));
            TaskService::createTask($model, $task_dto);
        }
        $ids = collect($is_new ? json_decode($request->contactSources) : $request->contactSources)->pluck('id')->toArray();
        $model->contactReportSources()->sync($ids);

        if (!empty($request->uploadedFiles) && $is_new) {
            if (!$model->files) {
                $model->files()->delete();
            }
            $files = $request->uploadedFiles;
            foreach ($files as $file) {
                $original_name = $file->getClientOriginalName();
                $size = $file->getSize();
                $file_name_to_store = $model->report_number . '__' . $original_name;
                Storage::disk('local')->putFileAs('contactReports/files/', $file, $file_name_to_store);
                $uploaded_file = new UploadedFile();
                $uploaded_file->storage_name = $file_name_to_store;
                $uploaded_file->viewable_name = $original_name;
                $uploaded_file->storage_size = $size;
                $uploaded_file->fileable()->associate($model);
                $uploaded_file->save();
            }
        }

        //Create inter-relationship between report, users, and company employees (or leads)
        //Delete existing relationship between contact report and company employees
        //Detach relational logic of contact report with customer company employees
        $model->companyEmployees()->detach();
        //Detach relational logic of contact report with docs hero emplyoees
        $model->employees()->detach();

        $model->companyEmployees()->attach($request->companyEmployees);
        $model->employees()->attach($request->employees);
        return $model;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *      path="/contact-reports/{id}",
     *      operationId="DeleteSingleContactReport",
     *      tags={"Contact Reports"},
     *      summary="Delete single contact reports",
     *      description="Remove the specified resource from storage",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of contacnt report record entry",
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
        $model = ContactReport::find($id);
        $model->delete();
        $content = [
            'moduleAction' => 'deleteContactReport',
            'data' => $model->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
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
        $model = ContactReport::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/contact-reports/company",
     *      operationId="getReportByCompany",
     *      tags={"Contact Reports"},
     *      summary="Get report by company id",
     *      description="Display a listing of the resource againts the specific company",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of company record",
     *          in="path",
     *          required=true,
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
    public function getReportByCompany(Request $request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new ContactReport();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->where('company_id', $request->id)->orderBy('contact_reports.created_at')->get();
        $data = $model->map(function ($item) {
            return [
                'id' => $item->id,
                'subject' => $item->subject,
                'text' => $item->text,
                'type' => $item->type,
                'priority' => $item->priority,
                'contactType' => $item->contact_type,
                'initiatedBy' => $item->initiated_by,
                'resubmitOn' => $item->resubmit_on,
                'company' => $item->company->company_name
            ];
        });
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}