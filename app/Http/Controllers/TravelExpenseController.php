<?php

namespace App\Http\Controllers;

use App\Models\GlobalSetting;
use App\Models\Project;
use App\Models\TravelExpense;
use App\Models\TravelExpenseApprover;
use App\Models\TravelExpenseReportDay;
use App\Models\UserProfileData;
use App\Resources\TravelExpenseBillResource;
use App\Resources\TravelExpenseDayResource;
use App\Resources\TravelExpenseTransportationResource;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class TravelExpenseController extends Controller
{
    use CustomHelper;

    /**
     *Runs when instance is initiated
     */

    public function __construct()
    {
        $this->middleware('check.permission:travel-expenses.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:travel-expenses.create', ['only' => ['store']]);
        $this->middleware('check.permission:travel-expenses.edit', ['only' => ['update']]);
        $this->middleware('check.permission:travel-expenses.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage;
        $model = ($this->hasRole($request, 'admin') && $request->input('userId')) ? TravelExpense::where('user_id', $request->input('userId')) : TravelExpense::where('user_id', $this->getAuthUserId($request));

        if ($request->companyId) {
            $model = $model->where("customer_id", $request->companyId);
        }

        if ($request->date) {
            $model->where(function ($query) use ($request) {
                $query->where('from_date', $request->date);
            });
        }

        if ($request->approved) {
            $models = $model->where('is_added', 0)->get();
        } else {
            $models = $model->paginate($per_page);
        }

        $model_collect = $models->map(function ($item) {
            $status = 'pending';
            if (!empty($item->travelExpenseApprovers->toArray())) {
                $status = 'pending';
                if ($item->travelExpenseApprovers?->count() == $item->travelExpenseApprovers?->where('status', 'approved')->count()) {
                    $status = 'approved';
                } elseif ($item->travelExpenseApprovers?->where('status', 'rejected')->count() > 0) {
                    $status = 'rejected';
                }
            }
            return [
                "id" => $item->id,
                "departureCity" => $item->departure_city,
                "departureCountry" => $item->departureCountry?->name,
                "arrivalCity" => $item->arrival_city,
                "arrivalCountry" => $item->arrivalCountry?->name,
                "fromDate" => $item->from_date,
                "toDate" => $item->to_date,
                "status" => $status,
                "isApprovalSent" => count($item->travelExpenseApprovers ?? []) > 0,
                'fromDepartureTime' => $item->from_departure_time,
                'fromArrivalTime' => $item->from_arrival_time,
                'toDepartureTime' => $item->to_departure_time,
                'toArrivalTime' => $item->to_arrival_time,
                'travelNumber' => $item->travel_number,
                'description' => $item->description,
                'toDiscrepancy' => $item->to_discrepancy,
                'fromDiscrepancy' => $item->from_discrepancy,
            ];
        });
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model_collect = $this->applySorting($model_collect, $sort_by, $sort_order);
        }
        if ($request->approved) {
            $model_collect = $model_collect->where('status', 'approved');
            return response()->json([
                'data' => [...$model_collect]
            ]);
        }

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
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "departureCity" => "required|string",
            "departureCountry" => "required|exists:countries,id",
            "arrivalCity" => "required|string",
            "arrivalCountry" => "required|exists:countries,id",

            "fromDate" => "required|date",
            "fromDepartureTime" => "required|string",
            "fromArrivalTime" => "required|string",

            "toDate" => "required|date",
            "toDepartureTime" => "required|string",
            "toArrivalTime" => "required|string",
            "description" => "required|string"
        ]);
        //Create Travel expense
        $model = new TravelExpense;
        $saveModel = $this->saveData($model, $request);
        if ($saveModel) {
            $this->addReportDays($saveModel);
            $message = '';
            if ($request->sendForApproval) {
                $approvers = $this->getApprover($saveModel, $saveModel->requestType->approval_level_1);
                $message = $approvers['message'];
                $this->storeApprovers($saveModel, $approvers['employeeIds'], $approvers['level']);
            }
            return response()->json(['message' => 'Record created successfully. ' . $message], 200);
        }
        return response()->json(['message' => 'Something went wrong.'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = TravelExpense::with('bills', 'transportations', 'daySpecifications')->find($id);

        return response()->json(["data" => [
            'id' => $model->id,
            //Define location from/to
            'departureCity' => $model->departure_city,
            "departureCountry" => $model->departure_country_id,
            "departureCountryName" => $model->departureCountry?->name,
            'arrivalCity' => $model->arrival_city,
            "arrivalCountry" => $model->arrival_country_id,
            "arrivalCountryName" => $model->arrivalCountry?->name,
            //Define from date/time information
            'fromDate' => $model->from_date,
            'fromDepartureTime' => $model->from_departure_time,
            'fromArrivalTime' => $model->from_arrival_time,
            'fromDiscrepancy' => $model->from_discrepancy,
            //Define to date/time information
            'toDate' => $model->to_date,
            'toDepartureTime' => $model->to_departure_time,
            'toArrivalTime' => $model->to_arrival_time,
            'toDiscrepancy' => $model->to_discrepancy,

            //Additional information
            'requestType' => $model->request_type_id,
            'projectId' => $model->project_id,
            'customerId' => $model->customer_id,
            'sendForApproval' => $model->status != 'draft', // Set the value based on the status
            'billDetail' => TravelExpenseBillResource::collection($model->bills),
            'transportationDetail' => TravelExpenseTransportationResource::collection($model->transportations),
            'daySpecificationDetail' => TravelExpenseDayResource::collection($model->daySpecifications),
            'description' => $model->description
        ]]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "departureCity" => "required|string",
            "departureCountry" => "required|exists:countries,id",
            "arrivalCity" => "required|string",
            "arrivalCountry" => "required|exists:countries,id",

            "fromDate" => "required|date",
            "fromDepartureTime" => "required|string",
            "fromArrivalTime" => "required|string",

            "toDate" => "required|date",
            "toDepartureTime" => "required|string",
            "toArrivalTime" => "required|string",
            "description" => "required|string"
        ]);

        //Create Travel expense
        $model = TravelExpense::find($id);
        $status = $this->getStatus($model);
        if ($status['status'] == 'pending' && $status['approvedCount'] == 0) {
            $saveModel = $this->saveData($model, $request);
            $this->addReportDays($saveModel, true);
            $message = '';
            if ($request->sendForApproval) {
                if (empty($saveModel->travelExpenseApprovers->toArray())) {
                    $approvers = $this->getApprover($saveModel, $saveModel->requestType->approval_level_1);
                    $message = $approvers['message'];
                    $this->storeApprovers($saveModel, $approvers['employeeIds'], $approvers['level']);
                } else {
                    $message = 'Approvers already added';
                }
            }
            return response()->json(['message' => 'Record updated successfully. ' . $message], 200);
        }
        return response()->json(['message' => 'One of the manger have already reviewed this travel expense.'], 400);
    }

    /**
     * Saves the data based on provided resource item
     *
     * @param object $model
     * @param object $request
     * @param array   Response
     */
    public function saveData($model, $request)
    {
        $current_user_id = $this->getAuthUserId($request);

        // Define location from/to
        $model->departure_city = $request->departureCity;
        $model->departure_country_id = $request->departureCountry;
        $model->arrival_city = $request->arrivalCity;
        $model->arrival_country_id = $request->arrivalCountry;

        // Additional Data
        $model->request_type_id = $request->requestType;
        $model->project_id = $request->projectId;
        $model->customer_id = $request->customerId;
        $model->description = $request->description;

        // Define from date/time information
        $model->from_date = Carbon::parse($request->fromDate);
        $model->from_departure_time = $request->fromDepartureTime;
        $model->from_arrival_time = $request->fromArrivalTime;
        $model->from_discrepancy = $request->fromDiscrepancy;

        // Define to date/time information
        $model->to_date = Carbon::parse($request->toDate);
        $model->to_departure_time = $request->toDepartureTime;
        $model->to_arrival_time = $request->toArrivalTime;
        $model->to_discrepancy = $request->toDiscrepancy;

        $model->user_id = $current_user_id;

        if (!$model->exists) {
            $is_global_settings_exist = GlobalSetting::where('key', 'travel_expense')->exists();
            if (!$is_global_settings_exist) {
                $global_setting = new GlobalSetting;
                $global_setting->key = 'travel_expense';
                $global_setting->value = 1;
                $global_setting->save();
                $new_value = $global_setting->value;
            } else {
                GlobalSetting::where('key', 'travel_expense')->increment('value');
                $new_value = GlobalSetting::where('key', 'travel_expense')->value('value');
            }
            $model->travel_number = 'TR-' . str_pad($new_value, 4, '0', STR_PAD_LEFT);
        }
        $model->save();
        return $model;
        //        $travel_expense_id = $model->id;
        //
        //        // Check if the travel expense needs approval
        //        $send_for_approval = $request->sendForApproval;
        //        if ($send_for_approval) {
        //            // Retrieve the request type and associated approvers
        //            $request_type_model = RequestType::find($request->requestType);
        //
        //            // Delete existing approvers for the travel expense
        //            TravelExpenseApprover::where('travel_expense_id', $travel_expense_id)->delete();
        //
        //            // Get the users who need to approve the travel expense
        //            $approvers = [];
        //
        //            $approval_levels = [
        //                $request_type_model->approval_level_1,
        //                $request_type_model->approval_level_2,
        //                $request_type_model->approval_level_3,
        //            ];
        //
        //            $user_data_model = UserProfileData::where("user_id", $current_user_id)
        //                ->with(["teams:id,team_lead_id,department_id", "teams.teamLead:id,user_id"])
        //                ->first();
        //
        //            foreach ($approval_levels as $level) {
        //                if (empty($level)) {
        //                    continue; // Skip empty approval levels
        //                }
        //
        //                if ($level === "team") {
        //                    $team_lead_ids = $user_data_model->teams->filter(function ($team) {
        //                        return !empty($team->teamLead);
        //                    })->pluck('teamLead.user_id')->toArray();
        //
        //                    $approvers = array_merge($approvers, $team_lead_ids);
        //                } elseif ($level === "department") {
        //                    $department_head_ids = $user_data_model->teams->filter(function ($team) {
        //                        return !empty($team->department) && !empty($team->department->departmentHead);
        //                    })->pluck('department.departmentHead.user_id')->toArray();
        //
        //                    $approvers = array_merge($approvers, $department_head_ids);
        //                } elseif ($level === "project") {
        //                    // Get the project manager of the selected project in $request->projectId
        //                    $project = Project::find($request->projectId);
        //                    if ($project) {
        //                        $approvers[] = $project->user_id;
        //                    }
        //                }
        //            }
        //
        //            // Get unique approver IDs and exclude the current user ID
        //            $unique_approvers = array_unique($approvers);
        //            $unique_approvers = array_diff($unique_approvers, [$current_user_id]);
        //
        //            // Store the approvers in the travel expense approvers table
        //            foreach ($unique_approvers as $approverId) {
        //                $approver = new TravelExpenseApprover();
        //                $approver->travel_expense_id = $travel_expense_id;
        //                $approver->approver_id = $approverId;
        //                $approver->status = 'pending';
        //                $approver->save();
        //            }
        //
        //            //Change status to pending
        //            $model->status = "pending";
        //            $model->save();
        //        }
        //        return $model;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $travelExpense = TravelExpense::find($id);
        if ($travelExpense) {
            $getStatus = $this->getStatus($travelExpense);
            if ($getStatus['status'] == 'pending' && $getStatus['approvedCount'] == 0) {
                $travelExpense->delete();
                return response()->json(['message' => 'Record deleted.'], 200);
            }
            return response()->json(['message' => 'Request is already reviewed.'], 400);
        }
        return response()->json(['message' => 'Invalid id provided.'], 400);
    }

    /**
     * Add the approvers of the travel expense if there is not any.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function sendForApproval($id)
    {
        $travelExpense = TravelExpense::find($id);
        if ($travelExpense) {
            if (count($travelExpense->travelExpenseApprovers ?? []) == 0) {
                $approvers = $this->getApprover($travelExpense, $travelExpense->requestType->approval_level_1);
                $this->storeApprovers($travelExpense, $approvers['employeeIds'], $approvers['level']);
                return response()->json(['message' => 'Successfully request sent to approvers. ' . $approvers['message']], 200);
            }
            return response()->json(['message' => 'Request is already sent to approvers.'], 400);
        }
        return response()->json(['message' => 'Invalid id provided.'], 400);
    }

    /**
     * Update the status for the approver
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function setApprovalStatus(Request $request)
    {
        $request->validate([
            "id" => "required",
            "status" => "required|string|in:pending,approved,rejected",
        ]);

        $approver = TravelExpenseApprover::find($request->id);
        if ($approver->status == 'pending') {
            $approver->status = $request->status;
            $approver->save();
            $travelExpenseApprovers = $approver->travelExpense->travelExpenseApprovers();
            $approvedStatusCount = $travelExpenseApprovers->where('status', 'approved')
                ->where('level', $approver->level)->count();
            if ($approvedStatusCount == $travelExpenseApprovers->count() && $approver->level < 3) {
                $approvalLevel = 'approval_level_' . $approver->level + 1;
                $approvers = $this->getApprover($approver->travelExpense, $approver->travelExpense->requestType->$approvalLevel, $approver->level + 1);
                $this->storeApprovers($approver->travelExpense, $approvers['employeeIds'], $approvers['level']);
                return response()->json(['message' => 'Status updated successfully. Next ' . $approvers['message']], 200);
            }
            return response()->json(['message' => 'Status updated successfully.'], 200);
        }
        return response()->json(['message' => 'Request is already ' . $approver->status], 400);
    }

    /**
     * Display a listing of the resource that needs approval.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getExpenseApprovalList(Request $request)
    {
        $user_id = $this->getAuthUserId($request);
        $per_page = !empty($request->perPage) ? $request->perPage : 25;

        $model = TravelExpenseApprover::where([
            "approver_id" => $user_id,
        ]);

        if ($request->get('status')) {
            $model->where('status', $request->get('status'));
        }

        $models = $model->paginate($per_page);
        $model_collect = $models->map(function ($item) {
            $user_profile = $item->travelExpense?->requester;
            return [
                "id" => $item->id,
                "travelExpenseId" => $item->travel_expense_id,
                "requester" => $user_profile?->full_name,
                'departureCity' => $item->travelExpense?->departure_city,
                'departureCountry' => $item->travelExpense?->departureCountry?->name,
                'arrivalCity' => $item->travelExpense?->arrival_city,
                'arrivalCountry' => $item->travelExpense?->arrivalCountry?->name,
                'fromDate' => $item->travelExpense?->from_date,
                'toDate' => $item->travelExpense?->to_date,
                "requestType" => $item->travelExpense?->requestType->name,
                "status" => $item->status,
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
        ], 200);
    }

    /**
     * Get the approvers ids while creating and updating status.
     *
     * @param $travelExpense , $approvalLevel, $level
     */
    private function getApprover($travelExpense, $approvalLevel, $level = 1)
    {
        $approversIds = [];
        $employee = UserProfileData::where('user_id', $travelExpense->user_id)->first();
        if ($approvalLevel && $approvalLevel == 'team') {
            $approversIds = $employee->teams?->whereNotNull('team_lead_id')->pluck('team_lead_id')->unique()->toArray();
        }

        if ($approvalLevel && $approvalLevel == 'department') {
            $approversIds = $employee->jobData?->userJobDepartments?->whereNotNull('department_head_id')->pluck('department_head_id')->unique()->toArray();
        }

        if ($approvalLevel && $approvalLevel == 'project') {
            if ($travelExpense->project_id) {
                $project = Project::find($travelExpense->project_id);
                if ($project) {
                    $employee = UserProfileData::where('user_id', $project->user_id)->first();
                    $approversIds = [$employee->user_id];
                }
            }
        }
        if (!empty($approversIds)) {
            return [
                'employeeIds' => $approversIds,
                "message" => "Travel expense approvers found at level " . $level,
                "level" => $level,
            ];
        }
        if ($level == 3) {
            return [
                "level" => $level,
                'employeeIds' => [],
                "message" => "No Travel expense approvers found.",
            ];
        }
        $level = $level + 1;
        $approvalLevel = 'approval_level_' . $level;
        return $this->getApprover($travelExpense, $travelExpense->requestType->$approvalLevel, $level);
    }

    /**
     * Store the approvers against the travel expense.
     *
     * @param $travelExpense , $approverIds, $level
     */

    private function storeApprovers($travelExpense, $approverIds, $level): void
    {
        if (count($approverIds) > 0) {
            foreach ($approverIds as $id) {
                $travelExpense->travelExpenseApprovers()->create([
                    'travel_expense_id' => $travelExpense->id,
                    'approver_id' => $id,
                    'status' => 'pending',
                    'level' => $level
                ]);
            }
        }
    }

    /**
     * Get the travel expense status from the approvers table.
     *
     * @param $travelExpense
     */

    private function getStatus($travelExpense)
    {
        $approvedCount = 0;
        $status = 'pending';
        if (!empty($travelExpense->travelExpenseApprovers->toArray())) {
            if ($travelExpense->travelExpenseApprovers?->count() == $travelExpense->travelExpenseApprovers?->where('status', 'approved')->count()) {
                $status = 'approved';
            } elseif ($travelExpense->travelExpenseApprovers?->where('status', 'rejected')->count() > 0) {
                $status = 'rejected';
            } else {
                $approvedCount = $travelExpense->travelExpenseApprovers?->where('status', 'approved')->count();
                $status = 'pending';
            }
        }
        return [
            'status' => $status,
            'approvedCount' => @$approvedCount
        ];
    }

    private function addReportDays($model, $isUpdate = false): void
    {
        $period = CarbonPeriod::create($model->from_date, $model->to_date);
        foreach ($period as $index => $date) {
            if ($date->isSameDay($model->from_date) && $date->isSameDay($model->to_date)) {
                $fromTime = $model->from_departure_time;
                $toTime = $model->to_arrival_time;
            } elseif ($date == $model->from_date && !$date->isSameDay($model->to_date)) {
                $fromTime = $model->from_departure_time;
                $toTime = "23:59";
            } elseif ($date != $model->from_date && !$date->isSameDay($model->to_date)) {
                $fromTime = "00:00";
                $toTime = "23:59";
            } elseif ($date != $model->from_date && $date->isSameDay($model->to_date)) {
                $fromTime = "00:00";
                $toTime = $model->to_arrival_time;
            }
            $expenseRate = 0;
            if ($model->arrivalCountry->name == 'Germany') {
                $fromDateTime = date('Y-m-d') . ' ' . $fromTime;
                $toDateTime = date('Y-m-d') . ' ' . $toTime;
                $absenceTime = Carbon::parse($fromDateTime)->diffInMinutes(Carbon::parse($toDateTime));
                $absenceTime = round($absenceTime / 60);
                // if one day trip and absence time is greater than 8 then add 14
                if (count($period->toArray()) == 1 && $absenceTime > 8) {
                    $expenseRate = 14;
                }

                // add 14 for arrival and departure days
                elseif ($index == 0 || count($period->toArray()) - 1 == $index) {
                    $expenseRate = 14;
                }

                // if absence time is 24 hours than add 28
                elseif ($index != 0 && count($period->toArray()) - 1 != $index && $absenceTime == 24) {
                    $expenseRate = 28;
                }
            }
            TravelExpenseReportDay::updateOrCreate([
                'date' => $date,
                'travel_expense_id' => $model->id,
            ], [
                'from_time' => $fromTime,
                'to_time' => $toTime,
                'expense_rate' => $expenseRate
            ]);
        }
        if ($isUpdate) {
            TravelExpenseReportDay::whereNotIn('date', $period->toArray())->where('travel_expense_id', $model->id)->delete();
        }
    }
}
