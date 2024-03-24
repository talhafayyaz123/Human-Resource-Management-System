<?php

namespace App\Http\Controllers;

use App\Helpers\OrderHelper;
use App\Helpers\GlobalSettingHelper;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\PerformanceRecord;
use App\Models\PerformanceRecordEntry;
use App\Models\TimeTrackerCompany;
use App\Models\UserProfileData;
use App\Traits\CustomHelper;
use App\Traits\SetGlobalNumber;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request as staticRequest;

class PerformanceRecordController extends Controller
{
    use SetGlobalNumber, CustomHelper;

    /**
     * Runs when instance is initiated
     */
    public function __construct()
    {
        $this->middleware('check.permission:performance-record.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:performance-record.create', ['only' => ['store']]);
    }

    /**
     * @OA\Get(
     *      path="/performance-records",
     *      operationId="getPerformancesList",
     *      tags={"Performances Records"},
     *      summary="Get list of Performances",
     *      description="Returns list of Performances",
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
     *         name="showUnapproved",
     *          description="Get the unaproved records",
     *          in="query",
     *          @OA\Schema(
     *              type="boolen"
     *          ),
     *      ),
     *     @OA\Parameter(
     *         name="companyId",
     *          description="Get the specific company records",
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

        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');


        $model = new PerformanceRecord();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        $performance_records = $model->filter(staticRequest::only('search', 'company', 'status'));

        if ($request->showUnapproved) {
            $performance_records->doesntHave('invoice');
        }

        if ($request->company) {
            $performance_records->where('company_id', $request->company);
        }

        //for customer portal
        if ($request->invoiceStatus) {
            $performance_records->where(function ($query) use ($request) {
                $query->whereHas('invoice', function ($q) use ($request) {
                    $q->where('status', $request->invoiceStatus);
                });
                $query->orWhere('status', 'approved');
            });
        }


        $models = $performance_records
            ->paginate($per_page);

        $model_collect = $models;

        if ($request->has('selectedIds')) {
            $model_collect = $this->getSelectedIds($model, $model_collect, $request->selectedIds);
        }


        $model_collect = $model_collect->map(function ($item) {
            return [
                'id' => $item->id,
                'company' => $item->customer?->company_name,
                'companyId' => $item->customer?->id,
                'advisorId' => $item->advisor_id,
                'companyNumber' => $item->customer?->company_number,
                'defaultServiceProduct' => $item->customer?->default_service_product ?? null,
                'defaultServiceHourlyRate' => $item->customer?->default_service_hourly_rate ?? null,
                'defaultServiceDailyRate' => $item->customer?->default_service_daily_rate ?? null,
                'discount' => $item->customer?->discount ?? null,
                'invoice' => $item?->invoice,
                'totalHours' => $item->total_hours,
                'goodWillHours' => $item->good_will_hours,
                'startDate' => $item->start_date,
                'endDate' => $item->end_date,
                'createdDate' => Carbon::parse($item->created_at)->format('Y-m-d'),
                'performanceNumber' => $item->performance_number,
                'status' => $item->status,
                'updatedAt' =>  Carbon::parse($item->updated_at)->toDateString(),
                'editedUserId' => $item->edited_user_id,
                'showCheckbox' => !$item->invoice && $item->customer?->default_service_product &&
                    $item->customer?->default_payment_period && $item->customer?->terms_of_payment,
                'isRejected' => $item->timeTrackerCompanies()->where('time_checking_status', false)->exists(),
                'isPaymentPeriodSet' => !empty($item->customer?->default_payment_period),
                'isDefaultServiceProductSet' => !empty($item->customer?->default_service_product),
                'isTermsOfPaymentSet' => !empty($item->customer?->terms_of_payment),
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
            ]
        ]);
    }

    /**
     * Shows the payment period record.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Get(
     *      path="/performance-records/show",
     *      operationId="getPerformancesSingleRecord",
     *      tags={"Performances Records"},
     *      summary="Get single record of Performances",
     *      description="Returns single record of Performances",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of performance record",
     *          in="query",
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
    public function show(Request $request)
    {
        $request->validate([
            "id" => "required",
        ]);

        // Retrieve the performance record
        $performance_record = PerformanceRecord::with([
            'performanceRecordEntries' => function ($query) {
                $query->orderByRaw('CAST(entry_order AS DECIMAL(65,30)) ASC');
            },
            'performanceRecordEntries.timeTrackerCompany'
        ])->findOrFail($request->id);

        // Retrieve the user profile data for all entries
        $entryUserIds = $performance_record->performanceRecordEntries->pluck('user_id')->unique();
        $userProfileData = UserProfileData::whereIn('user_id', $entryUserIds)->get()->keyBy('user_id');

        // Initialize arrays for tasks, ticket comments, ams and new entries
        $tasks = [];
        $ticket_comments = [];
        $ams = [];
        $new_entry = [];

        $total_hours = 0;
        $total_goodwill_hours = 0;

        // Process each entry
        foreach ($performance_record->performanceRecordEntries as $entry) {

            $time_tracker_company = $entry->timeTrackerCompany;

            if ($entry->is_goodwill == 0) {
                $total_hours += $entry->time;
            } else {
                $total_goodwill_hours += $entry->time;
            }

            // Check if the module is a task
            if (!is_null($time_tracker_company) && $time_tracker_company->module_type === "App\Models\Task") {
                $task = $time_tracker_company->module;
                $milestone = $task->milestone;
                $project = $milestone->project;

                // Retrieve the user's first name and last name from the pre-fetched data
                $user_profile = $userProfileData->get($entry->user_id);
                $person = $user_profile ? ($user_profile->first_name . ' ' . $user_profile->last_name) : '';

                // Create the mapped task object
                $mapped_task = [
                    'id' => $entry?->id,
                    'taskId' => $task?->id,
                    'userId' => $entry?->user_id,
                    'order' => floatval($entry->entry_order),
                    'date' => $entry?->date,
                    'person' => $person,
                    'description' => $entry?->description,
                    'internalNote' => $entry?->internal_note,
                    'quantity' => $entry?->time,
                    'isGoodwill' => $entry?->is_goodwill,
                    'milestone' => $milestone,
                    'project' => $project,
                    'timeCheckingStatus' => $time_tracker_company->time_checking_status,
                    'projectId' => $entry?->project_id,
                ];

                $tasks[] = $mapped_task;
            } // Check if the module is a ticket comment
            elseif (!is_null($time_tracker_company) && $time_tracker_company->module_type === "App\Models\TicketComment") {
                $ticket_comment = $time_tracker_company->module;

                // Retrieve the user's first name and last name from the pre-fetched data
                $user_profile = $userProfileData->get($entry->user_id);
                $person = $user_profile ? ($user_profile->first_name . ' ' . $user_profile->last_name) : '';

                // Create the mapped ticket comment object
                $mapped_ticket_comment = [
                    'id' => $entry?->id,
                    'ticketCommentId' => $ticket_comment->id,
                    'userId' => $entry?->user_id,
                    'order' => floatval($entry->entry_order),
                    'date' => $entry?->date,
                    'person' => $person,
                    'description' => $entry?->description,
                    'internalNote' => $entry?->internal_note,
                    'quantity' => $entry?->time,
                    'isGoodwill' => $entry?->is_goodwill,
                    'ticketNumber' => optional($ticket_comment->ticket)->ticket_number,
                    'timeCheckingStatus' => $time_tracker_company->time_checking_status,
                ];

                $ticket_comments[] = $mapped_ticket_comment;
            } // Check if the module is ams
            elseif (!is_null($time_tracker_company) && $time_tracker_company->module_type === "App\Models\ApplicationManagementService") {
                $amsEntry = $time_tracker_company->module;

                // Retrieve the user's first name and last name from the pre-fetched data
                $user_profile = $userProfileData->get($entry->user_id);
                $person = $user_profile ? ($user_profile->first_name . ' ' . $user_profile->last_name) : '';

                // Create the mapped ams object
                $mapped_ams = [
                    'id' => $entry?->id,
                    'amsId' => $amsEntry?->id,
                    'userId' => $entry?->user_id,
                    'order' => floatval($entry->entry_order),
                    'date' => $entry?->date,
                    'person' => $person,
                    'description' => $entry?->description,
                    'internalNote' => $entry?->internal_note,
                    'quantity' => $entry?->time,
                    'isGoodwill' => $entry?->is_goodwill,
                    'timeCheckingStatus' => $time_tracker_company->time_checking_status
                ];

                $ams[] = $mapped_ams;
            } // Check if the module is a newEntry with null module ID
            else {
                // Retrieve the user's first name and last name from the pre-fetched data
                $user_profile = $userProfileData->get($entry->user_id);
                $person = $user_profile ? ($user_profile->first_name . ' ' . $user_profile->last_name) : '';

                // Create the mapped new entry object
                $mapped_new_entry = [
                    'id' => $entry?->id,
                    'order' => floatval($entry?->entry_order),
                    'userId' => $entry?->user_id,
                    'date' => $entry?->date,
                    'person' => $person,
                    'userProfile' => $user_profile,
                    'description' => $entry?->description,
                    'internalNote' => $entry?->internal_note,
                    'quantity' => $entry?->time,
                    'isGoodwill' => $entry?->is_goodwill,
                    'timeCheckingStatus' => $time_tracker_company?->time_checking_status,
                    'projectId' => $entry?->project_id,
                    'isNewEntry' => true,
                ];

                $new_entry[] = $mapped_new_entry;
            }
        }

        // Group tasks by project and milestone
        $tasks_mapped = collect($tasks)->groupBy('project.id')->map(function ($project_tasks) {
            $project = $project_tasks->first()['project'];

            $milestones_mapped = $project_tasks->groupBy('milestone.id')->map(function ($milestone_tasks) {
                $milestone = $milestone_tasks->first()['milestone'];

                $tasks_mapped = $milestone_tasks->map(function ($task) {
                    unset($task['project'], $task['milestone']);
                    return $task;
                })->values();

                return [
                    'milestoneId' => $milestone['id'],
                    'name' => $milestone['name'],
                    'tasks' => $tasks_mapped,
                ];
            })->values();

            return [
                'projectId' => $project['id'],
                'name' => $project['name'],
                'milestones' => $milestones_mapped,
            ];
        })->values();

        return response()->json([
            'id' => $performance_record->id,
            'company' => $performance_record->customer->company_name,
            'companyNumber' => $performance_record->customer->company_number,
            'addressFirst' => $performance_record->customer->headQuarters?->address_first,
            'city' => $performance_record->customer->headQuarters?->city,
            'country' => $performance_record->customer->headQuarters?->country,
            'state' => $performance_record->customer->headQuarters?->state,
            'zip' => $performance_record->customer->headQuarters?->zip,
            'companyId' => $performance_record->customer->id,
            'projectId' => $performance_record->project_id,
            'performanceNumber' => $performance_record->performance_number,
            'startDate' => $performance_record->start_date,
            'isApprovedByCustomer' => $performance_record->is_approved_by_customer,
            'endDate' => $performance_record->end_date,
            'advisor' => $performance_record->advisor_id,
            'createdDate' => Carbon::parse($performance_record->created_at)->format('Y-m-d'),
            'isEdited' => $performance_record->is_edited,
            'updatedAt' => Carbon::parse($performance_record->updated_at)->toDateString(),
            'editedUserId' => $performance_record->edited_user_id,
            'tasks' => $tasks_mapped,
            'ticketComments' => $ticket_comments,
            'ams' => $ams,
            'newEntry' => $new_entry,
            'totalHours' => $total_hours,
            'totalGoodwillHours' => $total_goodwill_hours,
            'status' => $performance_record->status,
        ]);
    }


    /**
     * Store a new resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *      path="/store-performance-records",
     *      operationId="StorePerformaceRecords",
     *      tags={"Performances Records"},
     *      summary="Store performance records",
     *      description="Store performance records on bases of dates and advisor",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Json request",
     *    @OA\JsonContent(
     *       required={"startDate","endDate", "advisor"},
     *       @OA\Property(property="startDate", type="date", format="date", example="2023-01-01"),
     *       @OA\Property(property="endDate", type="date", format="date", example="2023-01-01"),
     *       @OA\Property(property="advisor", type="string", example="1234"),
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
            "startDate" => "required",
            "endDate" => "required",
            "advisor" => "required"
        ]);

        $response_data = $this->savePerfomanceRecord($request);
        //throwing an exception
        throw_if(collect($response_data)->isEmpty(), \Exception::class, 'No records existing');
        //if no record exist
        return response()->json([
            'success' => true,
            'message' => "Performance Record has been created",
            'data' => $response_data,
        ]);
    }

    /**
     * @OA\Post(
     *      path="/store-performance-records/manual",
     *      operationId="StorePerformaceRecordsManual",
     *      tags={"Performances Records"},
     *      summary="Store performance records",
     *      description="Store performance records manually",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Json request",
     *    @OA\JsonContent(
     *       required={"startDate","endDate", "companyId", "entries"},
     *       @OA\Property(property="startDate", type="date", format="date", example="2023-01-01"),
     *       @OA\Property(property="endDate", type="date", format="date", example="2023-01-01"),
     *       @OA\Property(property="companyId", type="string"),
     *       @OA\Property(property="projectId", type="int"),
     *       @OA\Property(property="advisorId", type="string"),
     *       @OA\Property(property="entries", type="array",
     *          @OA\Items(
     *     type="object",
     *     required={"description", "isGoodwill", "time","userId"},
     *                     @OA\Property(property="date", type="date", example=""),
     *                     @OA\Property(property="description", type="string", example="description"),
     *                     @OA\Property(property="isGoodwill", type="boolean", example="true"),
     *                     @OA\Property(property="time", type="string", example=""),
     *                     @OA\Property(property="userId", type="string", example="")
     *                 )
     *      ),
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
    public function storeManualPerformanceRecord(Request $request)
    {
        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'companyId' => 'required',
            'projectId' => 'nullable',
            'advisorId' => 'nullable',
            'entries' => 'required|array',
            'entries.*.date' => 'nullable',
            'entries.*.description' => 'required',
            'entries.*.isGoodwill' => 'required|boolean',
            'entries.*.time' => 'required',
            'entries.*.userId' => 'required'
        ]);

        DB::transaction(function () use ($request) {
            $performance_record = new PerformanceRecord();
            $performance_record->project_id = $request->projectId ?? null;
            $performance_record->start_date = $request->startDate;
            $performance_record->end_date = $request->endDate;
            $performance_record->advisor_id = $request->advisorId ?? null;
            $performance_record->status = $request->status ?? 'open';
            $performance_record->company_id = $request->companyId;
            $performance_record->is_approved_by_customer = $request->isApprovedByCustomer ?? false;
            $number = $this->globalNumber($performance_record, 'performance_record', Carbon::now()->year, 10000);
            $performance_record->performance_number = $number;
            $performance_record->total_hours = 0;
            $performance_record->good_will_hours = 0;
            $performance_record->save();
            $latest_order = null;
            foreach ($request->entries as $entry) {
                $performance_record_entry = new PerformanceRecordEntry();
                $performance_record_entry->date = $entry['date'];
                $performance_record_entry->description = $entry['description'];
                $performance_record_entry->is_goodwill = $entry['isGoodwill'];
                $performance_record_entry->task_number = $entry['taskNumber'] ?? null;
                $performance_record_entry->time = $entry['time'];
                $performance_record_entry->user_id = isset($entry['userId']['id']) ? $entry['userId']['id'] : null;
                $performance_record_entry->performance_record_id = $performance_record->id;
                if ($performance_record_entry->is_goodwill) {
                    $performance_record->good_will_hours += $performance_record_entry->time;
                } else {
                    $performance_record->total_hours += $performance_record_entry->time;
                }
                $latest_order = OrderHelper::order($latest_order ?? null);
                $performance_record_entry->entry_order = $latest_order;
                $performance_record_entry->save();
            }
            $performance_record->save();
        });
        return response()->json([
            'success' => true,
            'message' => "Performance Record has been created",
        ]);
    }

    /**
     * @OA\Post(
     *      path="/import-performance-records",
     *      operationId="ImportPerformaceRecords",
     *      tags={"Performances Records"},
     *      summary="Import performance records",
     *      description="Import performance records",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Json request",
     *    @OA\JsonContent(
     *       required={"csv","endDate", "companyId", "entries"},
     *       @OA\Property(property="csv", type="file"),
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
    public function importManualPerformanceRecords(Request $request)
    {
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt'
        ]);

        $data = $request->file('csv');
        $file = fopen($data, "r");
        $header_row = fgetcsv($file);
        // determine the column indices for the columns we need
        $summary_index = array_search('Zusammenfassung', $header_row);
        $process_key_index = array_search('Vorgangsschlüssel', $header_row);
        $project_lead_index = array_search('Projektleiter', $header_row);
        $date_index = array_search('Erledigt', $header_row);
        $time_index = array_search('Benötigte Zeit', $header_row);
        $original_estimate_index = array_search('Ursprüngliche Schätzung', $header_row);

        // loop through each line in the CSV file
        while (($row = fgetcsv($file)) !== false) {
            // extract the specific columns we need from the current row
            $summary = $summary_index !== false ? $row[$summary_index] : null; // Zusammenfassung
            $process_key = $process_key_index !== false ? $row[$process_key_index] : null; // Vorgangsschlüssel
            $project_lead = $project_lead_index !== false ? $row[$project_lead_index] : null; // ID des Projektleiters
            $date = $date_index !== false ? $row[$date_index] : null; // Erledigt
            $time = null;
            if ($time_index !== false && !empty($row[$time_index])) {
                $time = $row[$time_index] / 3600; // Benötigte Zeit (in minutes) divided by 3600
            } else if ($original_estimate_index !== false && !empty($row[$original_estimate_index])) {
                $time = $row[$original_estimate_index] / 3600; // Ursprüngliche Schätzung (in minutes) divided by 3600
            }

            // if we have valid data for all columns, add a new entry to the $data array
            if ($summary && $process_key && $project_lead && $date !== null && $time !== null) {
                $response_data[] = [
                    'activityDescription' => $summary,
                    'taskNumber' => $process_key,
                    'advisor' => $project_lead,
                    'date' => $date,
                    'time' => $time,
                ];
            }
        }

        fclose($file);


        return response()->json([
            'success' => true,
            'data' => $response_data ?? [],
        ]);
    }


    /**
     * Update the entries for the given ID.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Put(
     *      path="/performance-record-entries/update/{id}",
     *      operationId="UpdatePerformaceRecordEntries",
     *      tags={"Performances Records"},
     *      summary="Update performace record entries",
     *      description="Update performance records entries",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of performance record entry",
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
     *       required={"date","description", "isGoodwill", "time", "userId", "editedUserId"},
     *                     @OA\Property(property="date", type="date", example=""),
     *                     @OA\Property(property="description", type="string", example="description"),
     *                     @OA\Property(property="isGoodwill", type="boolean", example="true"),
     *                     @OA\Property(property="time", type="string", example=""),
     *                     @OA\Property(property="userId", type="string", example="")
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
    public function editEntries(int $id, Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string',
            'isGoodwill' => 'required|boolean',
            'time' => 'required|string',
            'userId' => 'required',
            'editedUserId' => 'required|string',

        ]);

        $performance_record_entry = PerformanceRecordEntry::findOrFail($id);
        $performance_record = $performance_record_entry->performanceRecord;
        $previous_time = $performance_record_entry->time;
        $new_time = $request->input('time');

        DB::transaction(function () use (&$performance_record, $previous_time, $new_time, $request, $performance_record_entry) {
            $performance_record_entry->date = $request->input('date');
            $performance_record_entry->description = $request->input('description');
            $performance_record_entry->internal_note = $request->input('internalNote');
            $performance_record_entry->is_goodwill = $request->input('isGoodwill');
            $performance_record_entry->time = $new_time;
            $performance_record_entry->user_id = isset($request->input('userId')["id"]) ? $request->input('userId')["id"] : null;
            /*   $performance_record_entry->edited_user_id =  $request->input('editedUserId') ?? null;
            $performance_record_entry->is_edited = true; */

            $performance_record->is_edited = true;
            $performance_record->edited_user_id = $request->input('editedUserId');
            if ($performance_record_entry->isDirty('is_goodwill')) {
                if ($performance_record_entry->is_goodwill) {
                    $performance_record->total_hours -= $previous_time;
                    $performance_record->good_will_hours += $new_time;
                } else {
                    $performance_record->good_will_hours -= $previous_time;
                    $performance_record->total_hours += $new_time;
                }
            } else {
                if ($performance_record_entry->is_goodwill) {
                    $performance_record->good_will_hours -= $previous_time;
                    $performance_record->good_will_hours += $new_time;
                } else {
                    $performance_record->total_hours -= $previous_time;
                    $performance_record->total_hours += $new_time;
                }
            }
            $performance_record_entry->save();
            $performance_record->save();
        });

        return response()->json([
            "success" => true,
            "message" => "Record has been updated"
        ]);
    }

    /**
     * add the entries to a performance record.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *      path="/performance-record-entries/store/{id}",
     *      operationId="StorePerformaceRecordEntries",
     *      tags={"Performances Records"},
     *      summary="Store performace record entries",
     *      description="Store performance records entries",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of performance record entry",
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
     *       required={"date","description", "isGoodwill", "time", "userId"},
     *                     @OA\Property(property="date", type="date", example=""),
     *                     @OA\Property(property="description", type="string", example="description"),
     *                     @OA\Property(property="isGoodwill", type="boolean", example="true"),
     *                     @OA\Property(property="time", type="string", example=""),
     *                     @OA\Property(property="upperOrder", type="int", example="")
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
    public function addEntries(int $id, Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string',
            'isGoodwill' => 'required|boolean',
            'time' => 'required|string',
            'userId' => 'required',
            "upperOrder" => "nullable|integer",
        ]);

        DB::transaction(function () use ($request, $id) {
            $performance_record = PerformanceRecord::findOrFail($id);
            $performance_record_entry = new PerformanceRecordEntry();
            $performance_record_entry->date = $request->date;
            $performance_record_entry->description = $request->description;
            $performance_record_entry->internal_note = $request->internalNote ?? '';
            $performance_record_entry->is_goodwill = $request->isGoodwill;
            $performance_record_entry->time = $request->time;
            $performance_record_entry->user_id = isset($request->input('userId')["id"]) ? $request->input('userId')["id"] : null;
            $performance_record_entry->performance_record_id = $performance_record->id;
            $order = OrderHelper::order($request->upperOrder ?? null);
            $performance_record_entry->entry_order = $order;
            $performance_record_entry->save();
            $performance_record->is_edited = true;
            $performance_record->edited_user_id = $request->editedUserId;
            if ($performance_record_entry->is_goodwill) {
                $performance_record->good_will_hours += $performance_record_entry->time;
            } else {
                $performance_record->total_hours += $performance_record_entry->time;
            }

            $performance_record->save();
        });
        return response()->json([
            "success" => true,
            "message" => "Record has been saved"
        ]);
    }

    /**
     * Changes the order of a record with the given ID.
     *
     * @param int $id The ID of the record to update.
     * @param Request $request The HTTP request object containing the new order value.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *      path="/performance-record-entries/change-order/{id}",
     *      operationId="ChangeOrderPerformaceRecordEntries",
     *      tags={"Performances Records"},
     *      summary="Change order of performace record entries",
     *      description="Change order performance records entries",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of performance record entry",
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
     *                     @OA\Property(property="upperOrder", type="int", example=""),
     *                     @OA\Property(property="lowerOrder", type="int", example=""),
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
    public function changeOrder(int $id, Request $request)
    {
        $request->validate([
            "upperOrder" => "nullable",
            "lowerOrder" => "nullable"
        ]);
        // Code to update the order of the record with the given ID.
        $performance_record_entry = PerformanceRecordEntry::findOrFail($id);
        $order = OrderHelper::order(
            $request->upperOrder ?? null,
            $request->lowerOrder ?? null
        );
        $performance_record_entry->entry_order = $order;
        $performance_record_entry->save();
        return response()->json(['message' => 'Performance entry moved successfully', 'order' => $performance_record_entry->entry_order], 200);
    }

    /**
     * Update a new resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Put(
     *      path="/performance-records/{id}",
     *      operationId="UpdatePerformaceRecord",
     *      tags={"Performances Records"},
     *      summary="Update performance record",
     *      description="Update performance record",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of performance record entry",
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
     *     required={"startDate","endDate", "editedUserId"},
     *                     @OA\Property(property="projectId", type="int", example=""),
     *                     @OA\Property(property="startDate", type="date", example=""),
     *     @OA\Property(property="endDate", type="date", example=""),
     *     @OA\Property(property="editedUserId", type="string", example=""),
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
            'projectId' => 'nullable',
            'startDate' => 'required',
            'endDate' => 'required|after_or_equal:startDate',
            'editedUserId' => 'required|string'
        ]);
        $performance_record = PerformanceRecord::findOrFail($id);
        if ($performance_record?->invoice) {
            throw new Exception("Performance record has already been invoiced");
        }
        $performance_record->project_id = $request->projectId ?? null;
        $performance_record->start_date = $request->startDate;
        $performance_record->end_date = $request->endDate;
        $performance_record->advisor_id = $request->advisorId;
        $performance_record->is_approved_by_customer = $request->isApprovedByCustomer ?? false;
        $performance_record->is_edited = true;
        $performance_record->status = $request->status ?? $performance_record->status;
        $performance_record->edited_user_id = $request->editedUserId;
        $performance_record->save();
        return response()->json([
            "success" => true,
            "message" => "Record has been updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *      path="/performance-records/{id}",
     *      operationId="DeletePerformaceRecord",
     *      tags={"Performances Records"},
     *      summary="Delete performance record",
     *      description="Delete performance record",
     *      @OA\Parameter(
     *          name="id",
     *          description="Id of performance record entry",
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
        $model = PerformanceRecord::findOrFail($id);
        if (!empty($model->performanceRecordEntries)) {
            foreach ($model->performanceRecordEntries as $entry) {
                if (isset($entry->timeTrackerCompany)) {
                    $entry->timeTrackerCompany()->update(['status' => 'new', 'performance_record_id' => NULL]);
                }
            }
            $model->performanceRecordEntries()->delete();
        }
        $model->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    /**
     * Save performance record based on given request parameters
     * @param Request $request
     * @return array|null
     * @throws Exception
     */
    private function savePerfomanceRecord(Request $request): ?array
    {
        $response_data = [];
        try {
            DB::transaction(
                function () use ($request, &$response_data) {
                    $start_date = $request->startDate;
                    $end_date = $request->endDate;
                    $time_tracker_companies = TimeTrackerCompany::when($request->company, function ($query) use ($request) {
                        $query->where('company_id', $request->company);
                    })->whereBetween('date', [$start_date, $end_date])
                        ->where('status', 'new')
                        ->where(function ($query) {
                            $query->where('is_ams_billable', 1)->orWhereNull('is_ams_billable');
                        })
                        ->get();
                    $flattened_data = collect();
                    // Group the records by company_id and project_id and sum the values of the 'time' and 'goodwill hours' columns for each group
                    $time_tracker_companies->groupBy(['company_id', 'project_id'])->map(function ($projects, $company_key) use (&$flattened_data) {
                        $projects->each(function ($group, $project_key) use (&$goodwill_hours, &$total_hours, &$flattened_data, $company_key) {
                            $project = Project::find($project_key);
                            if (!$project?->is_internal_project || $project?->is_internal_project == 0) {
                                $goodwill_hours = 0;
                                $total_hours = 0;
                                $group->each(function ($item) use (&$goodwill_hours, &$total_hours) {
                                    if ($item->is_goodwill) {
                                        $goodwill_hours += $item->time;
                                    } else {
                                        $total_hours += $item->time;
                                    }
                                });
                                $flattened_data->push([
                                    'company_id' => $company_key,
                                    'project_id' => !empty($project_key) ? $project_key : null,
                                    'goodwill_hours' => $goodwill_hours,
                                    'total_hours' => $total_hours,
                                ]);
                            }
                        });
                    });
                    //looping in companies data to store in performance record
                    if (!empty($flattened_data)) {
                        foreach ($flattened_data as $key => $data) {
                            $performance_record = new PerformanceRecord;
                            $performance_record->company_id = $data['company_id'];
                            $performance_record->project_id = $data['project_id'] ?? null;
                            $performance_record->total_hours = $data['total_hours'];
                            $performance_record->good_will_hours = $data['goodwill_hours'];
                            $performance_record->start_date = $request->startDate;
                            $performance_record->end_date = $request->endDate;
                            $performance_record->status = $request->status ?? 'open';
                            $performance_record->advisor_id = $request->advisor;
                            $performance_record->is_approved_by_customer = $request->isApprovedByCustomer ?? false;
                            $number = $this->globalNumber($performance_record, 'performance_record', Carbon::now()->year, 10000);
                            $performance_record->performance_number = $number;
                            $performance_record->save();
                            $performance_record->refresh();
                            $last_order = null;
                            $time_tracker_companies->where('company_id', $data['company_id'])->where('project_id', $data['project_id'])->each(function ($time_tracker_company) use ($performance_record, &$performance_record_entries, &$last_order, $data) {
                                $time_tracker_company->status = 'pr';
                                $time_tracker_company->performanceRecord()->associate($performance_record);
                                $time_tracker_company->save();
                                $performance_record_entry = new PerformanceRecordEntry();
                                $performance_record_entry->project_id = isset($data['project_id']) ? $data['project_id'] : null;
                                $performance_record_entry->date = $time_tracker_company->date;
                                $performance_record_entry->description = $time_tracker_company->description;
                                $performance_record_entry->is_goodwill = $time_tracker_company->is_goodwill;
                                $performance_record_entry->status = $time_tracker_company->status; // set status to 'pr'
                                $performance_record_entry->time = $time_tracker_company->time;
                                $performance_record_entry->user_id = $time_tracker_company->user_id ?? '';
                                $performance_record_entry->company_timetracker_id = $time_tracker_company->id;
                                $performance_record_entry->performance_record_id = $performance_record->id;
                                if ($last_order == null) {
                                    $last_order = OrderHelper::order(null, null); // set entry order for first record
                                    $performance_record_entry->entry_order = $last_order;
                                } else {
                                    $last_order = OrderHelper::order($last_order, null);
                                    $performance_record_entry->entry_order = $last_order;
                                    // set entry order for subsequent records
                                } // set performance record id as appropriate
                                $performance_record_entry->save();
                            });


                            $response_data = [
                                'id' => $performance_record->id,
                                'startDate' => $performance_record->start_date,
                                'endDate' => $performance_record->end_date,
                                'companyId' => $performance_record->company_id,
                                'advisorId' => $performance_record->advisor_id,
                            ];
                        }
                    }
                }
            );
            return $response_data;
        } catch (\Exception $e) {
            throw new Exception('An error occurred while processing your request. ' . $e->getMessage());
        }
    }

    public function createMultipleInvoices(Request $request)
    {
        $request->validate([
            'performanceRecordIds' => 'required|array',
            'performanceRecordIds.*' => 'int|exists:performance_records,id',
        ]);
        $performanceRecordIds = [];
        foreach ($request->performanceRecordIds as $id) {
            $performanceRecord = PerformanceRecord::findOrFail($id);
            $performanceRecord->status = 'done';
            $performanceRecord->save();
            $customer = $performanceRecord->customer;
            $paymentPeriod = $customer->defaultPaymentPeriod;
            $termOfPayment = $customer->termsOfPayment;

            if ($customer && $paymentPeriod && $termOfPayment && $customer->defaultServiceProduct) {

                $priceWithoutTax = ($performanceRecord->total_hours * $customer->default_service_hourly_rate) - (($performanceRecord->hours * $customer->default_service_hourly_rate) * $customer->discount / 100);

                $totalAmount = $priceWithoutTax;

                if (!$customer->apply_reverse_change) {
                    $totalAmount = $priceWithoutTax + ($priceWithoutTax * $customer->defaultServiceProduct?->tax / 100);
                }

                $invoice = new Invoice();
                $invoice->grouped_by = 'nothing';
                $invoice->payment_terms = $termOfPayment->invoice_text;
                $invoice->invoice_type = 'invoice';
                $invoice->invoice_category = 'service';
                $invoice->status = 'approved';
                $invoice->due_date = Carbon::now()->addDays($termOfPayment->no_of_days_1);
                $invoice->start_date = $performanceRecord->start_date;
                $invoice->end_date = $performanceRecord->end_date;
                $invoice->company_id = $customer->id;
                $invoice->user_id = $this->getAuthUserId($request);
                $invoice->invoice_period = $paymentPeriod->id;
                $invoice->project_id = $performanceRecord->project_id;
                $invoice->performance_record_id = $performanceRecord->id;
                $invoice->total_amount = $totalAmount;
                $invoice->total_amount_without_tax = $priceWithoutTax;
                $invoice->total_tax_amount = 0.00;
                if (!$customer->apply_reverse_change) {
                    $invoice->total_tax_amount = $priceWithoutTax * $customer->defaultServiceProduct?->tax / 100;
                }
                $invoice->terms_of_payment = $termOfPayment->id;
                $invoice->apply_reverse_change = $customer->apply_reverse_change;
                $invoice->save();

                $invoice->products()->attach($customer->default_service_product, [
                    'quantity' => $performanceRecord->total_hours,
                    'hourly_rate' => $customer->default_service_hourly_rate,
                    'discount' => $customer->discount,
                    'sale_price' => $customer->defaultServiceProduct?->sale_price,
                    'tax' => $customer->defaultServiceProduct?->tax,
                    'price_without_tax' => $priceWithoutTax,
                    'total_price' => $totalAmount,
                ]);
                $invoice_data = InvoiceService::invoiceForDocumentGeneration($invoice);
                $content = [
                    'moduleAction' => 'createInvoice',
                    'data' => $invoice_data,
                ];
                GlobalSettingHelper::sendEloAPIRequest($content);
            } else {
                $performanceRecordIds[] = $performanceRecord->id;
            }
        }
        if (count($performanceRecordIds) > 0) {
            return response()->json(['message' => 'Invoices created successfully except for these performance records .' . implode($performanceRecordIds), 'notCreatedIds' => $performanceRecordIds], 200);
        }
        return response()->json(['message' => 'Invoices created successfully.', 'notCreatedIds' => []], 200);
    }


    public function createPerformanceRecordCsv(Request $request, $id)
    {
        $performance_record = PerformanceRecord::findOrFail($id);


        $columns = [
            'Date', 'Description', 'Goodwill', 'Person/Reference', 'Quantity/Unit', 'Company',
        ];
        $token = $request->bearerToken();
        $config = config('services.users');
        $token = $request->bearerToken();
        $url = $config['vite_auth_service_url'];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($url . '/list-users', [
            'limit_start' => 0,
            'limit_count' => 25,
            'type' => 'employee'
        ]);
        $response = $response->json();
        $users = collect($response['data'] ?? []);
        $file_name = $performance_record->performance_number;
        $callback = function () use ($performance_record, $columns, $users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $time_tracker_entries = $performance_record->performanceRecordEntries;
            foreach ($time_tracker_entries as $time_tracker_entry) {

                $user = $users->where('id', $time_tracker_entry['user_id'])->first();
                $row['Date'] = $time_tracker_entry['date'];
                $row['Description'] =  $time_tracker_entry['description'];
                $row['Goodwill']  = $time_tracker_entry['is_goodwill'] ? 'Yes' : 'No';
                $row['Person'] = $user['first_name'] . " " . $user['last_name'];
                $row['Hours']  = $time_tracker_entry['time'];
                $row['Company']  = $performance_record->company?->company_name;
                fputcsv($file, [
                    $row['Date'],
                    $row['Description'],
                    $row['Goodwill'],
                    $row['Person'],
                    $row['Hours'],
                    $row['Company'],

                ]);
            }
            fclose($file);
        };
        return response()->streamDownload($callback, $file_name, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$file_name",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
            "filename" => $file_name . '.csv'
        ]);
    }



    public function showEloRequestForPerformanceRecord(Request $request)
    {
        $response = $this->show($request);
        $responseData = json_decode($response->getContent(), true);
        $responseData['base64'] = $request->base64 ?? '';
        $content = [
            'moduleAction' => 'performancePdfGeneration',
            'data' => $responseData,
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => 'Elo Request Successfully Sent.'], 200);
    }
}
