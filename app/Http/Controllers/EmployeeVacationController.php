<?php

namespace App\Http\Controllers;

use App\Helpers\LeaveHelper;
use App\Models\EmployeeVacation;
use App\Models\UserTeam;
use App\Models\UserDepartment;
use App\Models\GlobalSetting;
use App\Models\TimeTrackerGovernment;
use App\Models\EmployeeVacationApprover;
use App\Models\TimeTrackerUserData;
use App\Models\UserProfileData;
use App\Models\UserProfilePicture;
use App\Models\MailTemplateAssignment;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use App\Traits\GetUserProfilePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Carbon\CarbonPeriod;
use maxmeffert\feiertage\Feiertage;
use maxmeffert\feiertage\FeiertagEnum;

class EmployeeVacationController extends Controller
{
    use GetUserProfilePicture;
    use CustomHelper;

    /**
     * Run on instantiate
     */
    public function __construct()
    {
        $this->middleware('check.permission:employee-vacation.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:employee-vacation.create', ['only' => ['store']]);
        $this->middleware('check.permission:employee-vacation.edit', ['only' => ['update']]);
        $this->middleware('check.permission:employee-vacation.delete', ['only' => ['destroy', 'deleteApproval']]);
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

        $user_id = !$this->hasRole($request, 'admin') ? $this->getAuthUserId($request) : ($request->userId ?? $this->getAuthUserId($request));


        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new EmployeeVacation;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        if ($request->status) {
            $model = EmployeeVacation::join("employee_vacation_approver", "employee_vacations.id", "=", "employee_vacation_approver.employee_vacation_id")->where("status", $request->status);
        }
        $models = $model->filter(staticRequest::only('search', 'leaveType', 'startDate', 'endDate'))->where("user_id", $user_id)->paginate($per_page);
        $paginate_data = $models->toArray();
        $model_collect = $models->map(function ($item) {
            $replacements_with_names = UserProfileData::whereIn("id", $item?->replacements ?? [])->get()->map(function ($replacement) {
                return $replacement->first_name . " " . $replacement->last_name;
            })->toArray();
            $return = [
                'id' => $item->id,
                'startDate' => $item->start_date,
                'endDate' => ($item->leave_type == "whole" || $item->leave_type == "illness") ? $item->end_date : "",
                'leaveType' => $item->leave_type,
                'remarks' => $item->special_leave_comments,
                'requiredVacationDays' => $item->required_vacation_days ?? 0,
                'replacements' => !empty($replacements_with_names) ? implode(", ", $replacements_with_names) : '',
                'isPaid' => $item->is_paid,
                'isSpecialLeave' => $item->is_special_leave,
                'status' => 'pending',
                'approvers' => [],
                'cancelReason' => $item->cancel_reason,
            ];

            if ($item->vacation_status == 'cancel') {
                $return['status'] = 'cancelled';
            } else {
                $approvers = EmployeeVacationApprover::where('employee_vacation_id', $item->id)->get();
                $return['approvers'] = $approvers->map(function ($approver) {
                    $approver_profile = UserProfileData::where("user_id", $approver->approver_id)->first();
                    $url = $this->getProfilePicture($approver->approver_id ?? null) ?? null;
                    return [
                        "name" => (isset($approver_profile["first_name"]) ? $approver_profile["first_name"] : '') . ' ' . (isset($approver_profile["last_name"]) ? $approver_profile["last_name"] : ''),
                        "email" => isset($approver_profile["email"]) ? $approver_profile["email"] : '',
                        "mobile" => isset($approver_profile["mobile"]) ? $approver_profile["mobile"] : '',
                        "status" => $approver->status,
                        "userId" => @$approver_profile['user_id'],
                        "profilePicUrl" => $url
                    ];
                });
                if (isset($approvers[0])) {
                    $return['remarks'] = $approvers[0]?->remarks ?? "";
                }
                $reject_count = count($approvers->where('status', 'rejected'));
                $approve_count = count($approvers->where('status', 'approved'));
                if ($reject_count > 0) {
                    $return['status'] = 'rejected';
                }
                // if we have zero rejected status and one approver approves the request then this means this vacation
                // request has approved logic updated.
                elseif ($approve_count > 0) {
                    $return['status'] = 'approved';
                }
            }

            return $return;
        });
        return response()->json([
            'data' => $model_collect,
            'links' => $paginate_data['links'],
            'count' => $paginate_data['total'],
        ], 200);
    }

    /**
     * Pagination for specific user with sick leaves.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function illnessLeavesByUser(Request $request)
    {
        $per_page = $request->perPage;

        $model = EmployeeVacation::where("leave_type", "illness")->where("user_id", $request->userId)->paginate($per_page);
        $paginate_data = $model->toArray();
        $model_collect = $model->map(function ($item) {
            return [
                'id' => $item->id,
                'startDate' => $item->start_date,
                'endDate' => $item->end_date,
                'days' => $item->required_vacation_days,
                'notes' => $item->special_leave_comments,
            ];
        });
        return response()->json([
            'data' => $model_collect,
            'links' => $paginate_data['links'],
            'count' => $paginate_data['total'],
        ], 200);
    }

    public function deleteIllnessLeave($id){
        $vacation = EmployeeVacation::find($id);
      
        if ($vacation) {
            $vacation->employeeVacationApprover()->delete();
            $vacation->delete();
            return response()->json(['message' => 'Employee Illness Leave and all the approvals has been deleted']);
        }
        return response()->json(['message' => 'Invalid id provided'], 400);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //            DB::transaction(function () use ($request) {
            $request->validate([
                "leaveType" => "required|string",
                "replacements" => "required",
                "isSpecialLeave" => "required",
            ]);

            $user_id = !$this->hasRole($request, 'admin') ? $this->getAuthUserId($request) : $request->userId;

            if ($request->leaveType == "whole") {
                $request->validate([
                    "startDate" => "required",
                    "endDate" => "required",
                ]);
                $time_tracker_count = count(TimeTrackerGovernment::where("user_id", $user_id)->whereBetween("date", [$request->startDate, $request->endDate])->get());
                if ($time_tracker_count > 0) {
                    return response()->json([
                        "success" => true,
                        "message" => "Time tracker data has been already logged in for this date range"
                    ], 422);
                }
                $vacation_ranges = EmployeeVacation::where("user_id", $user_id)->where('vacation_status', '!=', 'cancel')->where(function ($query) use ($request) {
                    return $query->whereBetween("start_date", [$request->startDate, $request->endDate])
                        ->orWhereBetween("end_date", [$request->startDate, $request->endDate]);
                })->get()->map(function ($vacation) {
                    return $vacation->start_date . ' to ' . $vacation->end_date;
                });
                if (count($vacation_ranges) > 0) {
                    return response()->json([
                        "success" => true,
                        "message" => "You have already applied for a vacation from " . implode(", ", $vacation_ranges->toArray())
                    ], 422);
                }
            } elseif ($request->leaveType == "half") {
                $request->validate([
                    "startDate" => "required",
                ]);
            }

            if (Carbon::parse($request->startDate)->isWeekend() && Carbon::parse($request->endDate)->isWeekend() && ((Carbon::parse($request->startDate)->diffInDays(Carbon::parse($request->endDate)) + 1) == 2)) {
                return response()->json([
                    "success" => true,
                    "message" => "Cannot avail vacation for weekends"
                ], 422);
            }

            if ($request->isSpecialLeave) {
                $request->validate([
                    "specialLeaveType" => "required",
                ]);
            }

            $replacements = $request->replacements ?? [];

            // check if the replacement users do not have a vacation in the selected date range (startDate, endDate)
            foreach ($replacements as $replacement) {
                // find the user_profile_data based on the replacement
                $replacing_user_profile = UserProfileData::find($replacement);
                if ($replacing_user_profile) {
                    // look for any vacation between the startDate and endDate for the selected user_profile_data and count it
                    $vacation_count = $replacing_user_profile->employeeVacations()
                        ->whereBetween("start_date", [$request->startDate, $request->endDate])
                        ->whereBetween("end_date", [$request->startDate, $request->endDate])->count();
                    // if the count of vacations between the date ranges is greater than 0 then show an error message
                    if ($vacation_count > 0) {
                        return response()->json([
                            "success" => false,
                            "message" => $replacing_user_profile->first_name . " " . $replacing_user_profile->last_name . " has already applied for a vacation in this date range. Kindly select another replacement to continue."
                        ], 422);
                    }
                }
            }


            $monthly_start_date = Carbon::parse($request->startDate)->startOfMonth()->toDateString();
            $monthly_end_date = Carbon::parse($request->startDate)->endOfMonth()->toDateString();

            $total_working_days = 0;
            //Get User job data
            $profile_data = UserProfileData::where('user_id', $user_id)->first();
            if (!isset($profile_data->jobData->workingHours)) {
                return response()->json([
                    "message" => "Job data for the current user does not exist. Please create it first"
                ], 422);
            }
            $user_working_days = $profile_data->jobData->workingHours;
            foreach ($user_working_days as $item) {
                if ($item->day == "mon") {
                    $total_working_days = $total_working_days + Carbon::parse($monthly_start_date)->diffInDaysFiltered(function ($date) {
                            return $date->isMonday();
                        }, Carbon::parse($monthly_end_date));
                } elseif ($item->day == "tue") {
                    $total_working_days = $total_working_days + Carbon::parse($monthly_start_date)->diffInDaysFiltered(function ($date) {
                            return $date->isTuesday();
                        }, Carbon::parse($monthly_end_date));
                } elseif ($item->day == "wed") {
                    $total_working_days = $total_working_days + Carbon::parse($monthly_start_date)->diffInDaysFiltered(function ($date) {
                            return $date->isWednesday();
                        }, Carbon::parse($monthly_end_date));
                } elseif ($item->day == "thu") {
                    $total_working_days = $total_working_days + Carbon::parse($monthly_start_date)->diffInDaysFiltered(function ($date) {
                            return $date->isThursday();
                        }, Carbon::parse($monthly_end_date));
                } elseif ($item->day == "fri") {
                    $total_working_days = $total_working_days + Carbon::parse($monthly_start_date)->diffInDaysFiltered(function ($date) {
                            return $date->isFriday();
                        }, Carbon::parse($monthly_end_date));
                } elseif ($item->day == "sat") {
                    $total_working_days = $total_working_days + Carbon::parse($monthly_start_date)->diffInDaysFiltered(function ($date) {
                            return $date->isSaturday();
                        }, Carbon::parse($monthly_end_date));
                } elseif ($item->day == "sun") {
                    $total_working_days = $total_working_days + Carbon::parse($monthly_start_date)->diffInDaysFiltered(function ($date) {
                            return $date->isSunday();
                        }, Carbon::parse($monthly_end_date));
                }
            }

            $time_tracker_user_data = TimeTrackerUserData::firstOrNew([
                'monthly_start_date' => $monthly_start_date,
                'monthly_end_date' => $monthly_end_date,
                'actual_working_days' => $total_working_days,
                'user_id' => $user_id
            ]);

            $time_tracker_user_data->save();

            //Create Employee Vacation
            $model = new EmployeeVacation;

            $expiry_month_data = GlobalSetting::where("key", "LIKE", "expiryMonth")->first() ?? 0;

            $get_current_year_first_day = Carbon::now()->startOfYear()->toDateString();

            //Get total annual leaves: Job Section
            $total_annual_leaves = $profile_data?->jobData?->total_annual_leaves;
            /**
             * Get previous year data
             */
            $get_previous_year_first_day = Carbon::now()->subYear()->startOfYear()->toDateString();
            //Get all vacations for current year
            $previous_year_user_data = $profile_data->employeeTimeTrackerData()
                ->whereDate('monthly_start_date', '>=', $get_previous_year_first_day)
                ->whereDate('monthly_start_date', '<', $get_current_year_first_day)
                ->get();
            //Get holidays taken for the previous year
            $total_holidays_taken_previous = $previous_year_user_data?->sum(function ($item) {
                return $item->total_holidays_taken;
            });

            $taken_holidays = $profile_data->employeeVacations()->whereIn("leave_type", ["whole", "half"])
                ->where("is_paid", "=", 1)
                ->where("is_special_leave", 0)
                ->whereDate("start_date", ">=", $get_current_year_first_day)
                ->whereDate("end_date", "<=", Carbon::now()->endOfYear()->toDateString())->get();

            $approved_holidays = 0;

            foreach ($taken_holidays as $holiday) {
                $approvers = $holiday->vacationApprovers();
                $approvers_count = count($approvers->get());
                $approve_count = count($approvers->where('status', 'approved')->get());
                if ($approve_count == $approvers_count) {
                    $approved_holidays += $holiday->required_vacation_days;
                }
            }

            $sum = $total_annual_leaves;

            if (!empty($expiry_month_data) && Carbon::now()->month < $expiry_month_data->value) {
                $sum += $total_holidays_taken_previous;
            }

            $remaining_paid_leaves = ($sum + ($request->takenFromOvertime ?? 0)) - $approved_holidays;

            $total_requested_days = $request->leaveType == "whole" ? (Carbon::parse($request->startDate)->diffInDaysFiltered(function (Carbon $date) {
                    return $date->isWeekday();
                }, Carbon::parse($request->endDate))) + 1 : 0.5;

            $paid_leave_start_date = null;
            $paid_leave_end_date = null;
            $unpaid_leave_start_date = null;
            $unpaid_leave_end_date = null;

            if ($request->isSpecialLeave == 0) {
                if ($remaining_paid_leaves < $total_requested_days) {
                    $unpaid_leaves = $total_requested_days - $remaining_paid_leaves;
                    $paid_leave_start_date = Carbon::parse($request->startDate);
                    $paid_leave_end_date = Carbon::parse($request->startDate)->addDays($remaining_paid_leaves - 1);
                    $weekends = $paid_leave_start_date->diffInDaysFiltered(function (Carbon $date) {
                        return $date->isWeekend();
                    }, $paid_leave_end_date);
                    if ($paid_leave_start_date->isWeekend()) {
                        if ($paid_leave_start_date->isSaturday()) {
                            $paid_leave_start_date->addDays(2);
                            $paid_leave_end_date->addDays(2);
                        } else {
                            $paid_leave_start_date->addDays(1);
                            $paid_leave_end_date->addDays(1);
                        }
                    } else if ($paid_leave_end_date->isWeekend()) {
                        $weekends += 1;
                        $paid_leave_end_date->addDays($weekends);
                        if ($paid_leave_end_date->isWeekend()) {
                            $paid_leave_end_date->addDay();
                        }
                    } else {
                        $paid_leave_end_date->addWeekdays($weekends);
                    }
                    $unpaid_leave_start_date = Carbon::parse($paid_leave_end_date)->addWeekday();
                    $unpaid_leave_end_date = Carbon::parse($unpaid_leave_start_date)->addWeekday($unpaid_leaves - 1);

                    $this->saveData($model, $request, $paid_leave_start_date->toDateString(), $paid_leave_end_date->toDateString());

                    $unpaid_model = new EmployeeVacation();

                    $unpaid_model->is_paid = false;

                    $this->saveData($unpaid_model, $request, $unpaid_leave_start_date->toDateString(), $unpaid_leave_end_date->toDateString());

                    return response()->json(['message' => 'Record created successfully.'], 200);
                }

                if ($sum == $approved_holidays) {
                    $model->is_paid = false;
                }
            }
            $taken_from_overtime_hours = $this->calculateTakenFromOvertime($request, $profile_data->jobData->workingHours);
            $this->saveData($model, $request, null, null, $taken_from_overtime_hours);

            /**
             * if takenFromOvertime is greater than 0 then log TimeTrackerGovernment entries based on the vacation request startDate and the number of takenFromOvertime days
             */
            if ($request->takenFromOvertime > 0) {
                $period = CarbonPeriod::create($request->startDate, Carbon::parse($request->startDate)->addDays($request->takenFromOvertime - 1));
                foreach ($period as $index => $date) {
                    //log a vacation entry starting from "08:00:00"
                    // grab the last TimeTrackerGovernment entry if any for the $vacation date
                    $last_time_tracker_record = TimeTrackerGovernment::where('user_id', $request->userId)->where("date", $date)->orderBy("start_time", "desc")->first();
                    // get the end_time of the last_entry, if it does not exist than set the start_time to "08:00:00"
                    $start_time = Carbon::parse($last_time_tracker_record->end_time ?? "08:00:00");

                    // calculate end_time by adding to start_time
                    $end_time = $start_time->addHours($request->leaveType == 'whole' ? 8 : 4);

                    // if the end_time goes to the next day then set the end_time to 23:59:59
                    if ($end_time->day > $start_time->day) {
                        $end_time = Carbon::parse("23:59:59");
                    }
                    // create a vacation entry based on the start_time
                    TimeTrackerGovernment::create([
                        "type" => "take-from-overdue",
                        "description" => "taken from overtime",
                        "start_time" => $last_time_tracker_record->end_time ?? "08:00:00",
                        // if the vacation leave_type is "whole" then add 8 hours to the start_time to calculate the end_time
                        // for half leave add 4 hours to the start_time to calculate the end_time
                        "end_time" => $end_time,
                        "date" => $date,
                        "is_deletable" => 0,
                        "user_id" => $request->userId,
                        "employee_vacation_id" => $model?->id
                    ]);
                }
            }

            return response()->json(['message' => 'Record created successfully.', 'id' => $model->id], 200);
            //            });
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ], 500);
        }
    }

    /**
     * Create sick leave request for specific user.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createIllnessLeave(Request $request)
    {
        $request->validate([
            "startDate" => "required",
            "endDate" => "required",
            "userId" => "required",
        ]);

        $user_id = $request->userId;

        if ($user_id == $this->getAuthUserId($request)) {
            return response()->json([
                "success" => false,
                "message" => "The user cannot apply for his own illness"
            ], 422);
        }
        $time_tracker_count = count(TimeTrackerGovernment::where("user_id", $user_id)->whereBetween("date", [$request->startDate, $request->endDate])->get());
        if ($time_tracker_count > 0) {
            return response()->json([
                "success" => true,
                "message" => "Time tracker data has been already logged in for this date range"
            ], 422);
        }
        $vacation_ranges = EmployeeVacation::where("user_id", $user_id)->where(function ($query) use ($request) {
            return $query->whereBetween("start_date", [$request->startDate, $request->endDate])
                ->orWhereBetween("end_date", [$request->startDate, $request->endDate]);
        })->get()->map(function ($vacation) {
            return $vacation->start_date . ' to ' . $vacation->end_date;
        });
        if (count($vacation_ranges) > 0) {
            return response()->json([
                "success" => true,
                "message" => "You have already applied for a vacation from " . implode(", ", $vacation_ranges->toArray())
            ], 422);
        }

        if (Carbon::parse($request->startDate)->isWeekend() || Carbon::parse($request->endDate)->isWeekend()) {
            return response()->json([
                "success" => true,
                "message" => "Cannot avail vacation for weekends"
            ], 422);
        }

        //Create Employee Vacation
        $model = new EmployeeVacation;
        $model->leave_type = "illness";
        $model->start_date = $request->startDate;
        $model->end_date = $request->endDate;
        $model->required_vacation_days = $request->requiredVacationDays;
        $model->special_leave_comments = $request->notes;
        $model->user_id = $user_id;
        $model->save();

        return response()->json(['message' => 'Record created successfully.', 'id' => $model->id], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = EmployeeVacation::where("id", $id)->first();
        $approvers = EmployeeVacationApprover::where('employee_vacation_id', $model->id)->get();
        $approvers_list = $approvers->map(function ($approver) {
            $approver_profile = UserProfileData::where("user_id", $approver->approver_id)->first();
            $url = $this->getProfilePicture($approver->approver_id ?? null) ?? null;
            return [
                "name" => (isset($approver_profile["first_name"]) ? $approver_profile["first_name"] : '') . ' ' . (isset($approver_profile["last_name"]) ? $approver_profile["last_name"] : ''),
                "email" => isset($approver_profile["email"]) ? $approver_profile["email"] : '',
                "mobile" => isset($approver_profile["mobile"]) ? $approver_profile["mobile"] : '',
                "status" => $approver->status,
                "userId" => @$approver_profile['user_id'],
                "profilePicUrl" => $url
            ];
        });
        $replacements = UserProfileData::whereIn("id", $model?->replacements ?? [])->get()->map(function ($replacement) {
            return [
                "id" => $replacement->user_id,
                "name" => $replacement->first_name . " " . $replacement->last_name,
            ];
        })->toArray();
        return response()->json([
            'data' => [
                'id' => $model->id,
                'leaveType' => $model->leave_type,
                'startDate' => $model->start_date,
                'endDate' => $model->end_date,
                'requiredVacationDays' => $model->required_vacation_days,
                'replacements' => $replacements,
                'isSpecialLeave' => $model->is_special_leave,
                'specialLeaveType' => $model->special_leave_type,
                'specialLeaveComments' => $model->special_leave_comments,
                'isPaid' => $model->is_paid,
                'overtimeTaken' => round($model->take_from_overtime) . "h",
                'approvers' => $approvers_list
            ],
        ]);
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
            "leaveType" => "required|string",
            "replacements" => "required",
            "isSpecialLeave" => "required",
        ]);

        if ($request->leaveType == "whole") {
            $request->validate([
                "startDate" => "required",
                "endDate" => "required",
            ]);
        } elseif ($request->leaveType == "half") {
            $request->validate([
                "startDate" => "required",
            ]);
        }

        if ($request->isSpecialLeave) {
            $request->validate([
                "specialLeaveType" => "required",
            ]);
        }

        //Create Employee Vacation
        $model = EmployeeVacation::where("id", $id)->first();
        $this->saveData($model, $request);

        return response()->json(['message' => 'Record updated successfully.'], 200);
    }

    public function updateIllnessLeave(Request $request,$id)
    {
        $request->validate([
            "startDate" => "required",
            "endDate" => "required",

        ]);

        $model = EmployeeVacation::findOrFail($id);
        $model->start_date = $request->startDate;
        $model->end_date = $request->endDate;
        $model->required_vacation_days = $request->days;
        $model->special_leave_comments = $request->notes;
        $model->save();
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Illness Leave'])], 200);
    }


    /**
     * Saves the data based on provided resource item
     *
     * @param object $model
     * @param object $request
     * @param array   Response
     */
    public function saveData($model, $request, $start_date = null, $end_date = null, $overtimeHours = null)
    {
        $user_id = !$this->hasRole($request, 'admin') ? $this->getAuthUserId($request) : $request->userId;
        $model->leave_type = $request->leaveType;
        $model->start_date = $start_date ? $start_date : Carbon::parse($request->startDate);
        $model->end_date = $end_date ? $end_date : ($model->leave_type == 'half' ? Carbon::parse($request->startDate) : Carbon::parse($request->endDate));
        $model->required_vacation_days = $request->requiredVacationDays;
        $model->replacements = $request->replacements;
        $model->is_special_leave = $request->isSpecialLeave;
        $model->special_leave_comments = $request->specialLeaveComments;
        $model->special_leave_type = $request->specialLeaveType;
        $model->take_from_overtime = $overtimeHours;
        $model->user_id = $user_id;
        $model->save();
        $user_data_model = UserProfileData::where("user_id", $user_id)
            ->with(["teams:id,team_lead_id,department_id", "teams.teamLead:id,user_id"])
            ->first();

        $supervisors_ids = $user_data_model->getImmediateSupervisor();
        if (!empty($supervisors_ids)) {
            foreach ($supervisors_ids as $value) {
                $supervisors_ids_arr[] = $value['user_id'];
            }
            $model->vacationApprovers()->sync($supervisors_ids_arr);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = EmployeeVacation::find($id);
        $model->delete();
        $model->employeeVacationApprover->each->delete();
        $model->timeTrackerGovernments->each->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    /**
     * Restore the previously deleted source.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $model = EmployeeVacation::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }

    /**
     * Display a listing of the approvers list.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getApproveListing(Request $request)
    {
        $user_id = !$this->hasRole($request, 'admin') ? $this->getAuthUserId($request) : $request->userId;
        $approver_model = EmployeeVacationApprover::with('employeeVacations');
        // if no user_id then get all the records
        if ($user_id) {
            $approver_model = $approver_model->where("approver_id", $user_id);
        }
        $filtered_approvals = $approver_model;
        // apply filter only when there is a user_id
        if ($user_id) {
            $vacations = EmployeeVacation::where("user_id", $user_id)->pluck("id");
            $filtered_approvals = $approver_model->whereNotIn("employee_vacation_id", $vacations);
        }
        if (count($request->status ?? []) > 0) {
            $filtered_approvals = $filtered_approvals->whereIn("status", $request->status);
        }
        $uniqueArray = [];

        foreach ($filtered_approvals->get() as $key => $item) {
            $user_profile = UserProfileData::where("user_id", $item->employeeVacations?->user_id)->first();
            $supervisors_ids = $user_profile?->getImmediateSupervisor();
            $replacements_with_names = UserProfileData::whereIn("id", $item->employeeVacations?->replacements ?? [])->get()->map(function ($replacement) {
                return $replacement->first_name . " " . $replacement->last_name;
            })->toArray();

            $user_id = $item->employeeVacations?->user_id;
            $Vacation_id = $item->employeeVacations?->id;
            $total_supervisors = $supervisors_ids ? count($supervisors_ids) : 0;
            $combinedKey = $user_id . '|' . $Vacation_id . '|' . $total_supervisors;
            if (!isset($uniqueArray[$combinedKey])) {
                $uniqueArray[$combinedKey] = [
                    'id' => $item->id,
                    'user_id' => $user_id,
                    'Vacation_id' => $Vacation_id,
                    'status' => $item->status,
                    'remarks' => $item->remarks,
                    'total_supervisors' => $supervisors_ids ? count($supervisors_ids) : 0,
                    'requester' => ($user_profile?->first_name ?? '') . ' ' . ($user_profile?->last_name ?? ''),
                    'employeeVacationId' => $item->employeeVacations?->id,
                    'leaveType' => $item->employeeVacations?->leave_type,
                    'startDate' => $item->employeeVacations?->start_date,
                    'endDate' => $item->employeeVacations?->end_date,
                    'requiredVacationDays' => $item->employeeVacations?->required_vacation_days ?? 0,
                    'replacements' => !empty($replacements_with_names) ? implode(", ", $replacements_with_names) : '',
                    'isPaid' => $item->employeeVacations?->is_paid,
                    'isSpecialLeave' => $item->employeeVacations?->is_special_leave,
                    'specialLeaveType' => $item->employeeVacations?->special_leave_type,
                    'specialLeaveComments' => $item->employeeVacations?->special_leave_comments,
                    'overtimeTaken' => round($item->employeeVacations?->take_from_overtime) . "h",
                ];
            }
        }
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $uniqueArray = array_values($uniqueArray);

        if ($sort_by && $sort_order) {
            $uniqueArray = $this->applySorting(collect($uniqueArray), $sort_by, $sort_order);
        }
        return response()->json([
            'data' => $uniqueArray,
        ]);
    }

    /**
     * Update approver status
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function setApprovalStatus(Request $request)
    {
        $request->validate([
            "id" => "required",
            "status" => "required",
        ]);

        if ($request->status == 'rejected') {
            $request->validate([
                "remarks" => "required"
            ]);
        }

        DB::beginTransaction();
        try {

            //Create a record for the approver
            $approver_model = EmployeeVacationApprover::find($request->id);
            $approver_model->status = $request->status;
            $approver_model->remarks = $request->remarks;
            $approver_model->save();

            /**
             * When a vacation request is approved we must log TimeTrackerGovernment vacation entries for the given vacation date range
             */
            $approvers = $approver_model->employeeVacations->employeeVacationApprover; //all existing approvers
            $approved_count = $approvers->where('status', 'approved')->count(); // count of all the approvers who have set the status to "approved"
            $rejected_count = $approvers->where('status', 'rejected')->count(); // count of all the approvers who have set the status to "rejected"

            $status = 'pending';
            if ($rejected_count > 0) {
                $status = 'rejected';
            } elseif ($approved_count > 0) {
                $status = 'approved';
            }

            $requester_user_profile = collect(); //Initialize User profile to avoid multiple user profile query run
            /**
             * We can only have three scenarios:
             * 1) Add time tracker data - only needed if all approvers are pending and current is approved
             * 2) Remove time tracker data - only needed if atleast 1 approved and current is rejected
             * 3) No changes needed in time tracker data
             */
            $false = false;
            if ($status == 'approved') {
                /**
                 * Scenario: rejected count == 0 && approved count == 0 && current status = approved
                 * In this scenario we will add data in time tracker data
                 */
                $requester_user_profile = UserProfileData::where("user_id", $approver_model->employeeVacations->user_id)->first();
                if ($requester_user_profile->jobData->workingHours->count() == 0)
                    return response()->json(['error' => true, 'message' => 'Job data for the current user does not exist. Please create it first'], 400);

                $this->time_tracker_create_vacations($approver_model, $requester_user_profile);
                /**
                 * If the approver approved is not a special leave e.g. child birth/marriage/etc.
                 * Update requester remaining leaves
                 */
                if ($approver_model?->employeeVacations?->is_special_leave == 0) {
                    $remaining_leaves = $requester_user_profile->jobData->remaining_leaves;
                    $remaining_leaves -= $approver_model->employeeVacations?->required_vacation_days ?? 0;
                    $requester_user_profile->jobData->remaining_leaves = $remaining_leaves;
                    $requester_user_profile->jobData->save();
                }
            } elseif ($approved_count > 0 && $status == 'rejected') {
                /**
                 * Scenario: Rejected count == 0 && Approved Count > 0 && current status = rejected
                 * In this scenario we will remove data in time tracker data
                 */
                $requester_user_profile = UserProfileData::where("user_id", $approver_model->employeeVacations->user_id)->first();
                if ($requester_user_profile->jobData->workingHours->count() == 0)
                    return response()->json(['error' => true, 'message' => 'Job data for the current user does not exist. Please create it first'], 400);


                $this->time_tracker_remove_vacations($approver_model, $requester_user_profile);
                //Update requester remaining leaves
                $remaining_leaves = $requester_user_profile->jobData->remaining_leaves;
                $remaining_leaves += $approver_model->employeeVacations?->required_vacation_days ?? 0;
                $requester_user_profile->jobData->remaining_leaves = $remaining_leaves;
                $requester_user_profile->jobData->save();
            }

            // By default vacation is paid, the only time it is unpaid is when requested vacation is less than available leaves
            // Dev note: Disabled since there are a lot of issues in this logically
            /*
            if ($approver_model?->employeeVacations?->is_paid && $approver_model?->employeeVacations?->is_special_leave == 0) {
                $this->setUserTimeTrackerData($approver_model);
            }
            */
            DB::commit();

            return response()->json(['message' => 'Record updated.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Something went wrong please try again.'], 400);
        }
    }

    /**
     *
     */
    public function setUserTimeTrackerData($approver_model)
    {

        $employee_vacation_id = $approver_model->employee_vacation_id; //The ID of the current requested vacation
        $user_id = $approver_model->employeeVacations->user_id; //The ID of user requesting the vacation

        //Get all approves of existing vacation request
        $approvers = EmployeeVacationApprover::where('employee_vacation_id', $employee_vacation_id)->get();
        $status = 'pending'; //Default value
        $reject_count = count($approvers->where('status', 'rejected'));
        $approve_count = count($approvers->where('status', 'approved'));
        if ($reject_count > 0) {
            $status = 'rejected';
        } elseif ($approve_count == count($approvers ?? [])) {
            $status = 'approved';
        }

        if ($status != 'approved') {
            /**
             * Add logic to remove
             */
            return false;
        }

        $start_date = $approver_model->employeeVacations->start_date; //Start date of the vacation
        $end_date = $approver_model->employeeVacations->end_date; //End date of the vacation

        $start_month = Carbon::parse($start_date)->month;
        $end_month = Carbon::parse($end_date)->month;

        //Check if end month is different from starting month
        if ($end_month > $start_month) {
            /**
             * vacations from 25th Feb - 8rd March
             * get count from 25-28th feb, 1-8th march
             */
            //two months
            $month_start_of_starting_date = Carbon::parse($start_date)->startOfMonth()->toDateString(); //gets the first day of the current month
            $month_end_of_starting_date = Carbon::parse($start_date)->endOfMonth(); //gets the last day of the current month
            $diff_first = $month_end_of_starting_date->diffInDaysFiltered(function (Carbon $date) {
                return $date->isWeekday(); //Why? Saturday might be on as well?
            }, $start_date);

            if ($approver_model->employeeVacations->leave_type == "whole") {
                $diff_first += 1;
            } else {
                $diff_first = 0.5;
            }

            //Save data in user time tracker for the above dates
            $current_month_user_model = TimeTrackerUserData::firstOrNew([
                'monthly_start_date' => $month_start_of_starting_date,
                'monthly_end_date' => $month_end_of_starting_date->toDateString(),
                'user_id' => $user_id
            ]);
            $current_month_user_model->total_holidays_taken += $diff_first;
            $current_month_user_model->save();


            $month_start_of_ending_date = Carbon::parse($end_date)->startOfMonth(); //gets the first day of the next month
            $month_end_of_ending_date = Carbon::parse($end_date)->endOfMonth()->toDateString(); //gets the last day of the next month
            $diff_second = $month_start_of_ending_date->diffInDaysFiltered(function (Carbon $date) {
                return $date->isWeekday();
            }, $end_date);

            if ($approver_model->employeeVacations->leave_type == "whole") {
                $diff_second += 1;
            } else {
                $diff_second = 0.5;
            }

            //Save data in user time tracker for the above dates

            $next_month_user_model = TimeTrackerUserData::firstOrNew([
                'monthly_start_date' => $month_start_of_ending_date->toDateString(),
                'monthly_end_date' => $month_end_of_ending_date,
                'user_id' => $user_id
            ]);
            $next_month_user_model->total_holidays_taken += $diff_second;
            $next_month_user_model->save();
        } else {
            //One month
            $diff = Carbon::parse($start_date)->diffInDaysFiltered(function (Carbon $date) {
                return $date->isWeekday();
            }, $end_date);
            if ($approver_model->employeeVacations->leave_type == "whole") {
                $diff += 1;
            } else {
                $diff = 0.5;
            }
            $monthly_start_date = Carbon::parse($start_date)->startOfMonth()->toDateString(); //gets the first day of the current month
            $monthly_end_date = Carbon::parse($start_date)->endOfMonth()->toDateString(); //gets the last day of the current month
            $current_month_user_model = TimeTrackerUserData::firstOrNew([
                'monthly_start_date' => $monthly_start_date,
                'monthly_end_date' => $monthly_end_date,
                'user_id' => $user_id
            ]);
            $current_month_user_model->total_holidays_taken += $diff;
            $current_month_user_model->save();
        }
    }

    /**
     * Get daily working hours
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getVacationCalendar(Request $request)
    {

        $request->validate([
            "calendarType" => "required",
        ]);
        $calendar_type = $request->calendarType;

        if ($calendar_type === 'daily') {
            $request->validate([
                "calendarStartDate" => "required",
                "calendarEndDate" => "required",
            ]);
        } else {
            $request->validate([
                "calendarDay" => "required",
            ]);
        }

        /**
         * Other filters: term, status, leaveType, location, department, team
         * Sort by: employees, departments, teams
         */

        // Initialize data
        $per_page = !empty($request->userPerPage) ? $request->userPerPage : 10; //Get paginated data of users
        $user_id = $this->getAuthUserId($request);  //Get current user id
        $user_ids = collect(); //Set user ids data

        /**
         * If not admin. Expected functionality is to check if current user is "department head" and retrieve all requests under his team
         * If not admin. Expected functionality is to check if current user is "team lead" and retrieve all requests under his team
         * If not "team lead" or "department head". Expected functionality is to retrieve its own data.
         */

        if (!$this->hasRole($request, "admin")) {

            $user_ids = $user_ids->merge($user_id);
            //Get current user team lead id
            $department = UserDepartment::where("department_head_id", $user_id)->with("teams", "teams.teamMembers")->first();
            $team = UserTeam::where("team_lead_id", $user_id)->with("teamMembers")->first();
            if ($department) {
                foreach ($department->teams as $team) {
                    $user_ids = $user_ids->merge($team->teamMembers->pluck('user_id'));
                }
            } elseif ($team) {
                $user_ids = $user_ids->merge($team->teamMembers->pluck('user_id'));
            }
            $user_ids = $user_ids->unique();
        }

        $model = collect();

        if ($this->hasRole($request, "admin")) {
            $model = new UserProfileData;
        } else {
            $model = UserProfileData::whereIn("user_id", $user_ids);
        }

        if ($request->search) {
            $model = $model::where(function ($query) use ($request) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%$request->search%");
            });
        }

        if ($request->locationId) {
        }

        //adding query to remove cancel status vacation
        $models = $model->whereHas('employeeVacations', function ($query){
            $query->where('vacation_status', '!=', 'cancel');
        });

        //adding queries to filter
        $models = $model->when(!empty($request->leaveType), function ($query) use ($request) {
            $query->whereHas('employeeVacations', function ($query) use ($request) {
                $query->where('leave_type', $request->leaveType);
            });
        })->when(!empty($request->status), function ($query) use ($request) {
            if ($request->status !== 'pending') {
                $query->whereHas('employeeVacations.vacationApprovers', function ($query) use ($request) {
                    $query->where('status', $request->status);
                });
            } else {
                $query->whereHas('employeeVacations', function ($query) {
                    $query->whereDoesntHave('vacationApprovers');
                });
            }
        })->when(!empty($request->locationId), function ($query) use ($request) {
            $query->whereHas('jobData', function ($query) use ($request) {
                $query->where('user_location_id', $request->locationId);
            });
        })->when(!empty($request->teamId), function ($query) use ($request) {
            $query->whereHas('teams', function ($query) use ($request) {
                $query->where('user_team_id', $request->teamId);
            });
        })->when(!empty($request->departmentId), function ($query) use ($request) {
            $query->whereHas('teams', function ($query) use ($request) {
                $query->where('department_id', $request->departmentId);
            });
        })->paginate($per_page);
        $paginate_data = $models->toArray();

        if ($calendar_type === 'daily') {
            $model_collect = $this->getCalendarByDaily($models, $request);
        } elseif ($calendar_type === 'monthly') {
            $model_collect = $this->getCalendarByMonthly($models, $request);
        } elseif ($calendar_type === 'yearly') {
            $model_collect = $this->getCalendarByAnnually($models, $request);
        }

        return response()->json([
            'data' => $model_collect,
            'links' => $paginate_data['links'],
            'count' => $paginate_data['total'],
        ], 200);
    }

    /**
     * Get vacation calendar data by daily basis
     */
    public function getCalendarByDaily($models, $request)
    {
        return $models->map(function ($employee) use ($request) {

            //Get current employee vacations for the given dates
            $vacation_model = $employee->employeeVacations()
                ->where("end_date", ">=", $request->calendarStartDate)
                ->orWhere("start_date", "<=", $request->calendarEndDate);
            //Add filters for vacation models
            if (!empty($request->leaveType)) {
                $vacation_model = $vacation_model->where("leave_type", "LIKE", $request->leaveType);
            }
            if (!empty($request->status)) {
            }


            //Get vacation model dataset
            $vacation_model = $vacation_model->where("user_id", $employee->user_id)->get();
            $vacation_collect = $vacation_model->map(function ($vacation) {
                $approvers = EmployeeVacationApprover::where('employee_vacation_id', $vacation->id)->get();
                $status = 'pending';
                $reject_count = count($approvers->where('status', 'rejected'));
                $approve_count = count($approvers->where('status', 'approved'));
                if ($reject_count > 0) {
                    $status = 'rejected';
                } elseif ($approve_count == count($approvers ?? [])) {
                    $status = 'approved';
                }
                return [
                    'leaveType' => $vacation->leave_type,
                    'startDate' => $vacation->start_date,
                    'endDate' => $vacation->end_date,
                    'requiredVacationDays' => $vacation->required_vacation_days,
                    'status' => $status
                ];
            });

            //Get employee worked ours
            $time_tracker_model = $employee->employeeGovernmentHours
                ->whereIn("type", ["task", "ticket", "training"])
                ->map(function ($time_tracker) {
                    $end_time = Carbon::parse($time_tracker->end_time);
                    $start_time = Carbon::parse($time_tracker->start_time);
                    return [
                        'date' => $time_tracker->date,
                        'total_time' => $end_time->diffInHours($start_time),
                    ];
                })->groupBy('date');

            /**get specific user profile picture */
            $user_profile_pic_url = null;
            if (isset($employee->user_id)) {
                $user_id = $employee->user_id;
                $user = UserProfilePicture::where('user_id', $user_id)->first();
                $file = null;
                if (isset($user->storage_name)) {
                    $file = Storage::get('profile_pic/' . $user->storage_name);
                    $mime = Storage::mimeType('profile_pic/' . $user->storage_name);
                    if (isset($file)) {
                        $base64 = base64_encode($file);
                        $user_profile_pic_url = "data:$mime;base64,$base64";
                    }
                }
            }

            return [
                'userId' => $employee->user_id,
                'firstName' => $employee->first_name,
                'lastName' => $employee->last_name,
                'profilePictureUrl' => $user_profile_pic_url ?? null,
                'vacationDetails' => $vacation_collect->toArray(),
                'timeTrackerDetails' => $time_tracker_model->toArray(),
                'employeeJobDetails' => [
                    'workingDays' => !empty($employee->jobData) ? $employee->jobData->workingHours->map(function ($item) {
                        return [
                            'days' => [$item->day],
                            'numOfHours' => $item->working_hours
                        ];
                    }) : null,
                    'weeklyHours' => !empty($employee->jobData) ? $employee->jobData->weekly_hours : null,
                ],
                'teams' => $employee->teams->map(function ($team) {
                    return [
                        "id" => $team->id,
                        "name" => $team->name
                    ];
                }),
                'departments' => UserDepartment::whereIn("id", $employee->teams->pluck('department_id'))
                    ->get()->unique()->map(function ($department) {
                        return [
                            "id" => $department->id,
                            "name" => $department->name
                        ];
                    }),
            ];
        });
    }

    /**
     * Get vacation calendar data by monthly basis
     */
    public function getCalendarByMonthly($models, $request)
    {
        return $models->map(function ($employee) use ($request) {


            $start_day_of_month = Carbon::parse($request->calendarDay)->startOfMonth()->toDateString();
            $end_day_of_month = Carbon::parse($request->calendarDay)->endOfMonth()->toDateString();

            //Get current employee vacations for the given month
            $vacation_model = $employee->employeeVacations()
                ->where("start_date", ">=", $start_day_of_month)
                ->where("end_date", "<=", $end_day_of_month);

            //Add filters for vacation models
            if (!empty($request->leaveType)) {
                $vacation_model = $vacation_model->where("leave_type", "LIKE", $request->leaveType);
            }

            $vacation_collect = $vacation_model->where("user_id", $employee->user_id)->get()->map(function ($vacation) {
                $approvers = EmployeeVacationApprover::where('employee_vacation_id', $vacation->id)->get();
                $status = 'pending';
                $reject_count = count($approvers->where('status', 'rejected'));
                $approve_count = count($approvers->where('status', 'approved'));
                if ($reject_count > 0) {
                    $status = 'rejected';
                } elseif ($approve_count == count($approvers ?? [])) {
                    $status = 'approved';
                }
                return [
                    'leaveType' => $vacation->leave_type,
                    'startDate' => $vacation->start_date,
                    'endDate' => $vacation->end_date,
                    'requiredVacationDays' => $vacation->required_vacation_days,
                    'status' => $status
                ];
            });

            //Get employee worked ours
            $time_tracker_model = $employee->employeeGovernmentHours
                ->whereIn("type", ["task", "ticket", "training"])
                ->whereBetween('date', [$start_day_of_month, $end_day_of_month])
                ->map(function ($time_tracker) {
                    $end_time = Carbon::parse($time_tracker->end_time);
                    $start_time = Carbon::parse($time_tracker->start_time);
                    return [
                        'date' => $time_tracker->date,
                        'total_time' => $end_time->diffInHours($start_time),
                    ];
                })->groupBy('date');

            $weekly_worked_hours = [];
            foreach ($time_tracker_model as $date => $worked_days) {
                $week_number = Carbon::parse($date)->weekNumberInMonth;
                $total_time = $worked_days->sum("total_time");
                $weekly_worked_hours[$week_number] = isset($weekly_worked_hours[$week_number]) ? $weekly_worked_hours[$week_number] + $total_time : $total_time;
            }
            /**get specific user profile picture */
            $user_profile_pic_url = null;
            if (isset($employee->user_id)) {
                $user_id = $employee->user_id;
                $user = UserProfilePicture::where('user_id', $user_id)->first();
                $file = null;
                if (isset($user->storage_name)) {
                    $file = Storage::get('profile_pic/' . $user->storage_name);
                    $mime = Storage::mimeType('profile_pic/' . $user->storage_name);
                    if (isset($file)) {
                        $base64 = base64_encode($file);
                        $user_profile_pic_url = "data:$mime;base64,$base64";
                    }
                }
            }
            /** */
            return [
                'userId' => $employee->user_id,
                'firstName' => $employee->first_name,
                'lastName' => $employee->last_name,
                'profilePictureUrl' => $user_profile_pic_url ?? null,
                'vacationDetails' => $vacation_collect->toArray(),
                'timeTrackerDetails' => $time_tracker_model->toArray(),
                'weeklyWorkedDetails' => $weekly_worked_hours,
                'employeeJobDetails' => [
                    'workingDays' => !empty($employee->jobData) ? $employee->jobData->workingHours->map(function ($item) {
                        return [
                            'days' => [$item->day],
                            'numOfHours' => $item->working_hours
                        ];
                    }) : null,
                    'weeklyHours' => !empty($employee->jobData) ? $employee->jobData->weekly_hours : null,
                ],
                'teams' => $employee->teams->map(function ($team) {
                    return [
                        "id" => $team->id,
                        "name" => $team->name
                    ];
                }),
                'departments' => UserDepartment::whereIn("id", $employee->teams->pluck('department_id'))
                    ->get()->unique()->map(function ($department) {
                        return [
                            "id" => $department->id,
                            "name" => $department->name
                        ];
                    }),
            ];
        });
    }

    /**
     * Get vacation calendar data by annual basis
     */
    public function getCalendarByAnnually($models, $request)
    {
        return $models->map(function ($employee) use ($request) {

            $start_day_of_year = Carbon::parse($request->calendarDay)->startOfYear()->toDateString();
            $end_day_of_year = Carbon::parse($request->calendarDay)->endOfYear()->toDateString();

            //Get current employee vacations for the given year
            $user_data = $employee->employeeTimeTrackerData()
                ->where("monthly_start_date", ">=", $start_day_of_year)
                ->where("monthly_end_date", "<=", $end_day_of_year)
                ->get();

            //Get current employee vacations for the given month
            $vacation_model = $employee->employeeVacations()
                ->where("start_date", ">=", $start_day_of_year)
                ->where("end_date", "<=", $end_day_of_year);

            //Add filters for vacation models
            if (!empty($request->leaveType)) {
                $vacation_model = $vacation_model->where("leave_type", "LIKE", $request->leaveType);
            }

            $vacation_collect = $vacation_model->where("user_id", $employee->user_id)->get()->map(function ($vacation) {
                $approvers = EmployeeVacationApprover::where('employee_vacation_id', $vacation->id)->get();
                $status = 'pending';
                $reject_count = count($approvers->where('status', 'rejected'));
                $approve_count = count($approvers->where('status', 'approved'));
                if ($reject_count > 0) {
                    $status = 'rejected';
                } elseif ($approve_count == count($approvers ?? [])) {
                    $status = 'approved';
                }
                return [
                    'leaveType' => $vacation->leave_type,
                    'startDate' => $vacation->start_date,
                    'endDate' => $vacation->end_date,
                    'requiredVacationDays' => $vacation->required_vacation_days,
                    'status' => $status
                ];
            });

            $time_tracker_model = $employee->employeeGovernmentHours
                ->whereIn("type", ["task", "ticket", "training"])
                ->whereBetween('date', [$start_day_of_year, $end_day_of_year])
                ->map(function ($time_tracker) {
                    $end_time = Carbon::parse($time_tracker->end_time);
                    $start_time = Carbon::parse($time_tracker->start_time);
                    return [
                        'date' => $time_tracker->date,
                        'total_time' => $end_time->diffInHours($start_time),
                    ];
                })->groupBy('date');
            /**get specific user profile picture */
            $user_profile_pic_url = null;
            if (isset($employee->user_id)) {
                $user_id = $employee->user_id;
                $user = UserProfilePicture::where('user_id', $user_id)->first();
                $file = null;
                if (isset($user->storage_name)) {
                    $file = Storage::get('profile_pic/' . $user->storage_name);
                    $mime = Storage::mimeType('profile_pic/' . $user->storage_name);
                    if (isset($file)) {
                        $base64 = base64_encode($file);
                        $user_profile_pic_url = "data:$mime;base64,$base64";
                    }
                }
            }
            /** */

            return [
                'userId' => $employee->user_id,
                'firstName' => $employee->first_name,
                'lastName' => $employee->last_name,
                'profilePictureUrl' => $user_profile_pic_url ?? null,
                'timeTrackerDetails' => $time_tracker_model->toArray(),
                'vacationDetails' => $vacation_collect->toArray(),
                'monthlyWorkedDetails' => $user_data->map(function ($item) {
                    return [
                        'monthlyStartDate' => $item->monthly_start_date,
                        'monthlyEndDate' => $item->monthly_end_date,
                        'actualWorkedHours' => $item->actual_worked_hours,
                        'actualWorkingDays' => $item->actual_working_days,
                        'totalMonthlyHolidays' => $item->total_holidays_taken,
                    ];
                }),
                'employeeJobDetails' => [
                    'workingDays' => !empty($employee->jobData) ? $employee->jobData->workingHours->map(function ($item) {
                        return [
                            'days' => [$item->day],
                            'numOfHours' => $item->working_hours
                        ];
                    }) : null,
                    'weeklyHours' => !empty($employee->jobData) ? $employee->jobData->weekly_hours : null,
                ],
                'teams' => $employee->teams->map(function ($team) {
                    return [
                        "id" => $team->id,
                        "name" => $team->name
                    ];
                }),
                'departments' => UserDepartment::whereIn("id", $employee->teams->pluck('department_id'))
                    ->get()->unique()->map(function ($department) {
                        return [
                            "id" => $department->id,
                            "name" => $department->name
                        ];
                    }),
            ];
        });
    }

    /**
     * Vacation module profile data retrieval
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getVacationProfileData(Request $request)
    {
        /**
         * 2022: 30 days available holidays
         * 2022: 25 days taken holidays, 5 days remaining
         * 2023: 30 days available holidays (based on annual leaves) + 5 days carried over from 2022 => within first four months else expires 5 days
         * - Global setting to define at what month the previous year days expire
         * - Get remaining holidays previous year
         * - Get current month: if less than Nth months - not expired, else expired
         *
         *
         * - Remaining days: carried from previous year as well (applicable for first Nth months)
         * - Taken from it: How many holidays taken (holidays taken from previous available batch)
         * - Expired holidays: get all holidays not used in the current year (holidays not take AFTER Nth month)
         * ------
         * - Holidays 2023: job section total annual leaves
         * - Remaining holidays 2023: total left
         * - Additional leaves: Other than annual leaves (extra unpaid leaves)
         * - Special leave 2023: Type "special"
         */

        $user_id = ($request->userId && $this->hasRole($request, 'admin')) ? $request->userId : $this->getAuthUserId($request);
        $get_current_year_first_day = Carbon::now()->startOfYear()->toDateString();

        $model_profile = UserProfileData::where("user_id", $user_id)->first();
        //Get total annual leaves: Job Section
        $total_annual_leaves = $model_profile?->jobData?->total_annual_leaves;

        //Get all vacations for current year
        $current_year_user_data = $model_profile->employeeTimeTrackerData()->where('monthly_start_date', '>=', $get_current_year_first_day)->get();
        //Get actual worked hours for the current year
        $total_worked_hours = $current_year_user_data?->sum(function ($item) {
            return $item->actual_worked_hours;
        });
        //Get actual working days for the current year
        $total_working_days = $current_year_user_data?->sum(function ($item) {
            return $item->actual_working_days;
        });
        //Get remaining leaves: Job Section
        $remaining_leaves = $model_profile?->jobData?->remaining_leaves;
        //Get holidays taken for the current year
        $total_holidays_taken_current = $current_year_user_data?->sum(function ($item) {
            return $item->total_holidays_taken;
        });
        $additional_leaves = 0;
        if ($model_profile) {
            $getLeaveData = LeaveHelper::getLeaveInfo($model_profile->id);
            $remaining_leaves = $getLeaveData['totalRemainingLeaves'];
            $total_holidays_taken_current = $getLeaveData['totalLeavesTaken'];
            $additional_leaves = $getLeaveData['additionalLeaves'];
            $previous_year_remaining_leaves = $getLeaveData['previousYearRemainingLeaves'];
            $current_year_remaining = $getLeaveData['totalRemainingLeaves'] - $getLeaveData['previousYearRemainingLeaves'];
            $expire_leaves = $getLeaveData['expiresLeaves'];
        }
        //Get special leave
        $total_special_leave = $model_profile->employeeVacations()
            ->where([
                ['start_date', '>=', $get_current_year_first_day],
                ['is_special_leave', '=', 1],
            ])->get()
            ->count();

        /**
         * Get previous year data
         */
        $get_previous_year_first_day = Carbon::now()->subYear()->startOfYear()->toDateString();
        //Get all vacations for current year
        $previous_year_user_data = $model_profile->employeeTimeTrackerData()
            ->whereDate('monthly_start_date', '>=', $get_previous_year_first_day)
            ->whereDate('monthly_start_date', '<', $get_current_year_first_day)
            ->get();
        //Get holidays taken for the previous year
        $total_holidays_taken_previous = $previous_year_user_data?->sum(function ($item) {
            return $item->total_holidays_taken;
        });

        $vacations = EmployeeVacation::where("user_id", $user_id)->whereIn("leave_type", ["whole", "half"])->whereDate("start_date", '>=', $get_current_year_first_day)
            ->whereDate("end_date", '<=', Carbon::now()->endOfYear()->toDateString())->get();

        $pending = 0;
        $approved = 0;
        $illness = 0;
        $unpaid = 0;

        $illnessLeaves = EmployeeVacation::where("user_id", $user_id)->where("leave_type", "illness")->whereDate("start_date", '>=', $get_current_year_first_day)
            ->whereDate("end_date", '<=', Carbon::now()->endOfYear()->toDateString())->get();
        foreach ($illnessLeaves as $vacation) {
            $illness += $vacation->required_vacation_days;
        }

        foreach ($vacations as $vacation) {
            $approvers = EmployeeVacationApprover::where('employee_vacation_id', $vacation->id)->get();
            $reject_count = count($approvers->where('status', 'rejected'));
            $approve_count = count($approvers->where('status', 'approved'));
            $status = "pending";
            if ($reject_count > 0) {
                $status = 'rejected';
            } elseif ($approve_count == count($approvers ?? [])) {
                $status = 'approved';
            }
            if ($status == "pending") {
                if ($vacation->is_paid) {
                    $pending += ($vacation->leave_type == "whole" ? $vacation->required_vacation_days : 0.5);
                }
            } else if ($status == "approved") {
                if ($vacation->is_paid) {
                    $approved += ($vacation->leave_type == "whole" ? $vacation->required_vacation_days : 0.5);
                } else {
                    $unpaid += ($vacation->leave_type == "whole" ? $vacation->required_vacation_days : 0.5);
                }
            }
        }

        return response()->json([
            'totalAnnualLeaves' => $total_annual_leaves,
            'previousYearRemainingLeaves' => @$previous_year_remaining_leaves,
            'currentYearRemainingLeaves' => @$current_year_remaining,
            'expiredLeaves' => @$expire_leaves,
            'remainingLeaves' => $remaining_leaves,
            'totalWorkedHours' => $total_worked_hours,
            'totalWorkingDays' => $total_working_days,
            'totalHolidaysTakenCurrent' => $total_holidays_taken_current,
            'currentAdditionalLeaves' => $additional_leaves,
            'totalHolidaysTakenPrevious' => $total_holidays_taken_previous,
            'totalSpecialLeave' => $total_special_leave,
            'pending' => $pending,
            'approved' => $approved,
            'illness' => $illness,
            'unpaid' => $unpaid
        ], 200);
    }

    /**
     * sends the mail using the Http client
     * @param Request $request
     * @param integer $id
     * @return JSONResponse
     */
    public function sendMail(Request $request, $id)
    {
        try {
            $model = EmployeeVacation::findOrFail($id);
            $approvers = UserProfileData::whereIn('user_id', $model->employeeVacationApprover()->pluck('approver_id') ?? [])->get();
            $replacements = UserProfileData::whereIn('id', $model->replacements ?? [])->get();
            $user = UserProfileData::where('user_id', $model->user_id)->first();
            $template = MailTemplateAssignment::where('module', 'vacationRequestTemplate')->first();
            if (!$template) {
                throw new \Exception('Mail template is not set for vacation request');
            }
            $payload = [
                "id" => $template->mail_template_id,
                "mails" => $approvers->pluck('email')->toArray(),
                "from" => $template->sender_mail,
                "cc" => $template->cc ? [$template->cc] : [],
                "bcc" => $template->bcc ? [$template->bcc] : [],
                "data" => [
                    "vacationRequest" => [
                        "id" => $model->id,
                        "leaveType" => $model->leave_type,
                        "startDate" => $model->start_date,
                        "endDate" => $model->end_date,
                        "requiredVacationDays" => $model->required_vacation_days,
                        "specialLeaveType" => $model->special_leave_type,
                        "replacements" => $replacements,
                        "requester" => $user,
                        "isSpecialLeave" => $model->is_special_leave,
                        "isPaid" => $model->is_paid,
                        "specialLeaveComments" => $model->special_leave_comments,
                        "createdAt" => $model->created_at
                    ],
                    "files" => []
                ]
            ];
            $config = config('services.mail');
            $response = Http::withToken($request->bearerToken())
                ->post($config['vite_mail_service_url'] . '/mail-service/send-mail', $payload);
            if ($response->successful()) {
                return response()->json([
                    "success" => true,
                    "message" => isset($response->json()["msg"]) ? $response->json()["msg"] : ""
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => isset($response->json()["msg"]) ? $response->json()["msg"] : ""
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ], 500);
        }
    }

    public function deleteApproval($id)
    {
        $approval = EmployeeVacationApprover::find($id);
        if ($approval) {
            $approval->employeeVacations->employeeVacationApprover()->delete();
            $approval->employeeVacations()->delete();
            return response()->json(['message' => 'Employee vacation and all the approvals has been deleted']);
        }
        return response()->json(['message' => 'Invalid id provided'], 400);
    }

    /**
     * Logic to create time tracker data for the selected user and approved vacation
     */
    public function time_tracker_create_vacations($approver_model, $requester_user_profile)
    {
        $requester_id = $requester_user_profile->user_id;
        $vacation = $approver_model->employeeVacations; // the current vacation
        $start_date = Carbon::parse($vacation->start_date); // start date of the vacation
        $end_date = Carbon::parse($vacation->end_date); // end date of the vacation
        $start_time = '08:00:00';

        // Get all days the user is suppose to work
        $user_working_days = $requester_user_profile->jobData->workingHours;

        $current_date = Carbon::parse($start_date);

        while ($current_date->lte(Carbon::parse($end_date))) {
            $day_of_week = strtolower($current_date->format('D'));

            // Check if the current day is a working day
            $working_day_info = $user_working_days->first(function ($working_day) use ($day_of_week) {
                return $working_day['day'] === $day_of_week;
            });
            // If it's a working day, calculate start and end times
            if ($working_day_info) {
                $start_date_time = Carbon::parse($current_date->toDateString() . ' ' . $start_time);
                $end_date_time = $start_date_time->copy()->addHours($working_day_info->working_hours);
                $description = 'vacation';
                if ($requester_user_profile->country == 'Germany') {
                    if (Feiertage::check($current_date)) {
                        $checkType = Feiertage::which($current_date);
                        foreach (FeiertagEnum::keys() as $index => $key) {
                            if ($key == $checkType) {
                                $description = $index;
                                break;
                            }
                        }
                    }
                }
                TimeTrackerGovernment::firstOrCreate([
                    'type' => 'vacation',
                    'date' => $current_date->toDateString(),
                    'start_time' => $start_time,
                    'end_time' => $end_date_time->format('H:i:s'),
                    'user_id' => $requester_id,
                ], [
                    'description' => $description,
                    'internal_note' => null,
                    'time_tracker_company_id' => null,
                ]);
            }

            // Move to the next day
            $current_date->addDay();
        }
    }

    /**
     * Logic to remove time tracker data for the selected user and rejected vacation
     */
    public function time_tracker_remove_vacations($approver_model = null, $requester_user_profile, $vacation = null)
    {
        $requester_id = $requester_user_profile->user_id;
        if (!$vacation) {
            $vacation = $approver_model->employeeVacations; // the current vacation
        }
        $start_date = Carbon::parse($vacation->start_date); // start date of the vacation
        $end_date = Carbon::parse($vacation->end_date); // end date of the vacation
        $start_time = '08:00:00';

        // Get all days the user is suppose to work
        $user_working_days = $requester_user_profile->jobData->workingHours;

        $current_date = Carbon::parse($start_date);
        while ($current_date->lte(Carbon::parse($end_date))) {
            $day_of_week = strtolower($current_date->format('D'));

            // Check if the current day is a working day
            $working_day_info = $user_working_days->first(function ($working_day) use ($day_of_week) {
                return $working_day->day === $day_of_week;
            });
            // If it's a working day, calculate start and end times
            if ($working_day_info) {
                $start_date_time = Carbon::parse($current_date->toDateString() . ' ' . $start_time);
                $end_date_time = $start_date_time->copy()->addHours($working_day_info->working_hours);

                TimeTrackerGovernment::where([
                    'type' => 'vacation',
                    'date' => $current_date->toDateString(),
                    'start_time' => $start_time,
                    'end_time' => $end_date_time->format('H:i:s'),
                    'user_id' => $requester_id,
                ])->delete();
            }

            // Move to the next day
            $current_date->addDay();
        }
    }

    /**
     * Logic to calculate the total hours on the base of days that are coming in request
     */
    public function calculateTakenFromOvertime($request, $user_working_days)
    {
        if ($request->takenFromOvertime) {
            $totalDays = $request->takenFromOvertime;
            $datePeriod = CarbonPeriod::create(Carbon::parse($request->startDate), Carbon::parse($request->endDate))->toArray();
            if (!last($datePeriod)->is(Carbon::parse($request->endDate))) {
                $datePeriod[] = Carbon::parse($request->endDate);
            }
            $totalWorkingHours = 0;
            for ($i = 0; $i < $totalDays; $i++) {
                $date = $datePeriod[$i];
                $day_of_week = strtolower($date->format('D'));
                if ($day_of_week == 'sun' || $day_of_week == 'sat') {
                    $totalDays++;
                    continue;
                }
                // Check if the current day is a working day
                $working_day_info = $user_working_days->first(function ($working_day) use ($day_of_week) {
                    return $working_day->day === $day_of_week;
                });
                $totalWorkingHours += $working_day_info->working_hours;
            }
            return $totalWorkingHours;
        }
        return 0;
    }


    public function createLegalHolidays(Request $request)
    {
        $request->validate([
            'userId' => 'nullable'
        ]);
        $current_date = Carbon::now();
        if ($request->userId) {
            //get the user profile
            $requester_user_profile = UserProfileData::where('user_id', $request->userId)->first();
            //check if the day is a working day
            $user_working_days = $requester_user_profile?->jobData?->workingHours;
            $day_of_week = strtolower($current_date->format('D'));
            $working_day_info = $user_working_days?->first(function ($working_day) use ($day_of_week) {
                return $working_day['day'] === $day_of_week;
            });
            if (isset($working_day_info))
                $this->createLegalHoliday($current_date, $working_day_info, $request->userId);
        } else {
            $user_profiles = UserProfileData::all();
            $user_profiles->map(function ($user_profile) use ($current_date) {
                if ($user_profile->user_id) {
                    $user_working_days = $user_profile?->jobData?->workingHours;
                    $day_of_week = strtolower($current_date->format('D'));
                    $working_day_info = $user_working_days?->first(function ($working_day) use ($day_of_week) {
                        return $working_day['day'] === $day_of_week;
                    });
                    if (isset($working_day_info))
                        $this->createLegalHoliday($current_date, $working_day_info, $user_profile->user_id);
                }
            });
        }
        return response()->json(['message' => $request->userId ? 'Vacation created for this specific user' : 'Vacation created for all users if its a vacation day']);
    }

    private function createLegalHoliday($current_date, $working_day_info, $user_id)
    {
        $start_time = '08:00:00';
        $start_date_time = Carbon::parse($current_date->toDateString() . ' ' . $start_time);
        $end_date_time = $start_date_time->copy()->addHours($working_day_info->working_hours);
        $description = 'vacation';
        if (Feiertage::check($current_date)) {
            $checkType = Feiertage::which($current_date);
            foreach (FeiertagEnum::keys() as $index => $key) {
                if ($key == $checkType) {
                    $description = $index;
                    break;
                }
            }
            TimeTrackerGovernment::firstOrCreate([
                'type' => 'vacation',
                'date' => $current_date->toDateString(),
                'start_time' => $start_time,
                'end_time' => $end_date_time->format('H:i:s'),
                'user_id' => $user_id,
            ], [
                'description' => $description,
                'internal_note' => null,
                'time_tracker_company_id' => null,
            ]);
            return true;
        }
        return false;
    }


    public function cancelVacation(Request $request)
    {
        $request->validate([
            'vacationId' => 'required|exists:employee_vacations,id',
            'cancelReason' => 'required|string',
        ]);
        if ($this->hasRole($request, 'admin')) {
            $employeeVacation = EmployeeVacation::find($request->vacationId);
            $employeeVacation->vacation_status = 'cancel';
            $employeeVacation->cancel_reason = $request->cancelReason;
            $employeeVacation->save();

            if ($employeeVacation->vacation_status == 'cancel') {
                EmployeeVacationApprover::where('employee_vacation_id', $employeeVacation->id)->delete();
                $requester_user_profile = UserProfileData::where("user_id", $employeeVacation->user_id)->first();
                $this->time_tracker_remove_vacations(null, $requester_user_profile, $employeeVacation);
                return response()->json(['message' => 'Vacation cancelled successfully']);
            }
            return response()->json(['message' => 'Something went wrong'], 400);
        }
        return response()->json(['message' => 'You dont have the access to delete this.'], 400);
    }

}
