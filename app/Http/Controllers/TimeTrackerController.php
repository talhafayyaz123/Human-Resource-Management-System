<?php

namespace App\Http\Controllers;

use App\Exports\TimeTrackerExport;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\Company;
use Carbon\CarbonPeriod;
use App\Helpers\LeaveHelper;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use App\Models\TicketComment;
use App\Models\TravelExpense;
use App\Models\UserProfileData;
use App\Models\EmployeeVacation;
use App\Helpers\TimeTrackerHelper;
use App\Models\TimeTrackerCompany;
use App\Rules\HoursValidationRule;
use Illuminate\Support\Facades\DB;
use App\Models\TimeTrackerUserData;
use App\Models\TimeTrackerGovernment;
use App\Models\EmployeeVacationApprover;
use App\Models\ApplicationManagementService;
use App\Models\UserJobData;
use App\Http\Controllers\ConsultingDashboardController;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TimeTrackerController extends Controller
{

    use CustomHelper;

    /**
     * Run on instantiate
     */
    public function __construct()
    {
        $this->middleware('check.permission:time-tracker.list', ['only' => ['getTimeTrackerData']]);
        $this->middleware('check.permission:time-tracker.create', ['only' => ['saveRecords']]);
        $this->middleware('check.permission:finance-dashboard', ['only' => ['timeTrackerStatistics']]);
        Carbon::setLocale('en');
    }

    private function getOldWorkingHours($request, $user_id)
    {
        /** Get user previous record */
        $previous_month_user_data = TimeTrackerUserData::where(['user_id' => $user_id, ['monthly_end_date', '<=', Carbon::parse($request->date)->startOfMonth()->subMonthNoOverflow()->endOfMonth()]])->get();

        //Get the total worked hours for the given month
        $previous_month_worked_hours_total = $previous_month_user_data->sum('actual_worked_hours') ?? 0;

        $profile_data = UserProfileData::where('user_id', $user_id)->first();

        $old_worked_hours = 0;

        if (!empty($profile_data->jobData)) {
            if (isset($profile_data->jobData->accounting_date)) {
                $supposed_work_hours = $this->getSupposedHoursWorked(Carbon::parse($profile_data->jobData->accounting_date), Carbon::parse($request->date)->startOfMonth()->subMonthNoOverflow()->endOfMonth(), $profile_data->jobData->workingHours);
            } else {
                $supposed_work_hours = 0;
            }
            //caluclating overtime of the previous month and total
            $old_worked_hours = $previous_month_worked_hours_total - $supposed_work_hours;
        }

        /** Get previous overdue taken */
        $previous_overdue_taken = $this->getPreviousOverdueTaken($user_id, Carbon::parse($request->date)->endOfMonth());

        return $old_worked_hours - $previous_overdue_taken;
    }

    /**
     * Get the tracker data baesd on the provided filters
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function getTimeTrackerData(Request $request)
    {
        $request->validate([
            "date" => "required",
        ]);

        $current_week_first_day = Carbon::parse($request->date)->startOfWeek()->toDateString();
        $current_week_last_day = Carbon::parse($request->date)->endOfWeek()->toDateString();
        $current_month_first_day = Carbon::parse($request->date)->startOfMonth()->toDateString();
        $current_month_last_day = Carbon::parse($request->date)->endOfMonth()->toDateString();
        $from_date = strtotime($current_month_first_day) < strtotime($current_week_first_day) ? $current_month_first_day : $current_week_first_day;

        /**
         * Get data that exists
         */
        //Get government dataset
        $government_model = new TimeTrackerGovernment;

        //Get user id
        $user_id = $request->userId;
        if (!$user_id) {
            $user_id = $this->getAuthUserId($request);
        }

        //Get government data by user id
        $government_model = $government_model->orderByRaw('start_time, end_time')->where('user_id', $user_id)
            ->whereBetween('date', [$from_date, $current_month_last_day])
            ->get();

        $government_data = $government_model->where('date', $request->date)->map(function ($item) {
            return [
                "id" => $item->id,
                "type" => $item->type,
                "customerId" => $item->company_id,
                "customerType" => ($item->type == "sales-presales" && $item->company_id != '') ? $item->customer->customer_type : '',
                "description" => $item->description,
                "internalNote" => $item->internal_note,
                "startTime" => $item->start_time,
                "endTime" => $item->end_time,
                // "commentId" => $item->comment_id ?? null,
                "timeTrackerCompanyId" => $item->government_timetracker_id ?? null,
                "status" => $item->timeTrackerCompany?->status ?? "new",
                "isDeletable" => $item->is_deletable
            ];
        })->values();
        //Get company dataset
        $company_model = new TimeTrackerCompany;

        if (!empty($request->company)) {
            $company_model = $company_model->where('company_id', $request->company != 'all' ? $request->company : '');
        }

        //Get company data by user id
        $company_model = $company_model->where('user_id', $user_id)
            ->whereBetween('date', [$from_date, $current_month_last_day])
            ->get();

        $previous_time = 0;
        $company_data = $company_model->where('date', $request->date)->map(function ($item) use (&$previous_time) {
            $total_time = $previous_time + $item->time;
            $previous_time = $total_time;
            $additionalInfo = [];
            if ($item->module_type == 'App\Models\TicketComment') {
                $module_type = 'ticket';
                $additionalInfo = [
                    "ticketNumber" => $item->module->ticket->ticket_number,
                    "amsId" => $item->ams_id
                ];
            } elseif ($item->module_type == 'App\Models\Task') {
                $module_type = 'task';
                $additionalInfo = [
                    "project" => $item->module->milestone->project,
                    "milestone" => $item->module->milestone
                ];
            } elseif ($item->module_type == 'App\Models\ApplicationManagementService') {
                $module_type = 'ams';
            } elseif ($item->module_type == 'App\Models\TravelExpense') {
                $module_type = 'travel-expense';
                $additionalInfo = [
                    "travelNumber" => $item->module?->travel_number,
                ];
            } else if ($item->module_type == 'newEntry') {
                $module_type = $item->module_type;
                $additionalInfo = [
                    "project" => $item->project ? [
                        'id' => $item->project->id,
                        'name' => $item->project->name,
                    ] : null,
                ];
            } else {
                $module_type = $item->module_type;
            }
            return [
                "id" => $item->id,
                "moduleType" => $module_type,
                "moduleId" => $item->module_id ?? null,
                "description" => $item->description,
                "internalNote" => $item->internal_note,
                "time" => $item->time,
                "company" => $item->company_id,
                "isGoodwill" => $item->is_goodwill,
                "status" => $item->status,
                "previousTime" => $previous_time,
                "relatedGovernmentEndTime" => $item?->governments->pluck('end_time')->max() ?? null,
                "additionalInfo" => $additionalInfo
            ];
        })->values();

        /** Get user previous record */
        $previous_month_user_data = TimeTrackerUserData::where(['user_id' => $user_id, ['monthly_end_date', '<=', Carbon::parse($request->date)->startOfMonth()->subMonthNoOverflow()->endOfMonth()]])->get();
        //Get the total worked hours for the given month
        $previous_month_worked_hours_total = $previous_month_user_data->sum('actual_worked_hours') ?? 0;


        /** Get previous overdue taken */
        $previous_overdue_taken = $this->getPreviousOverdueTaken($user_id, Carbon::parse($request->date)->endOfMonth());

        //Get User weekly hours
        $profile_data = UserProfileData::where('user_id', $user_id)->first();
        $current_worked_hours = 0;
        if (!empty($profile_data->jobData)) {
            if (isset($profile_data->jobData->accounting_date)) {
                $supposed_work_hours = $this->getSupposedHoursWorked(Carbon::parse($profile_data->jobData->accounting_date), Carbon::parse($request->date)->startOfMonth()->subMonthNoOverflow()->endOfMonth(), $profile_data->jobData->workingHours);
            } else {
                $supposed_work_hours = 0;
            }

            //caluclating overtime of the previous month and total
            $old_worked_hours = $previous_month_worked_hours_total - $supposed_work_hours - $previous_overdue_taken;

            /** Get user current month record */
            // $current_month_user_data = TimeTrackerUserData::where(['user_id' => $user_id, ['monthly_start_date', '=', $current_month_first_day]])->first();
            if (isset($profile_data->jobData->accounting_date)) {
                $accounting_date = $profile_data->jobData->accounting_date;
                if (Carbon::parse($accounting_date)->toDateString() <= $current_month_first_day) {
                    $supposed_work_hours = $this->getSupposedHoursWorked($current_month_first_day, Carbon::parse($request->date), $profile_data->jobData->workingHours);
                } else {
                    $date = Carbon::parse($accounting_date);
                    $current_month = Carbon::parse($request->date)->month;
                    if ($date->month == $current_month) {
                        $supposed_work_hours = $this->getSupposedHoursWorked($date, Carbon::parse($request->date), $profile_data->jobData->workingHours);
                    } else {
                        $supposed_work_hours = 0;
                    }
                }
            } else {
                $supposed_work_hours = 0;
            }
            //caluclating overtime of the current month

            //Get dataset of all worked hour records
            $month_hours_model = TimeTrackerGovernment::where('user_id', $user_id)
                ->where("type", "!=", "break")
                ->whereBetween('date', [$current_month_first_day, Carbon::parse($request->date)->toDateString()])->get();
            //Get the total worked hours for the given month
            $month_worked_hours = $month_hours_model->sum(function ($item) {
                if (isset($item->start_time) && isset($item->end_time)) {
                    $end_time = Carbon::parse($item->end_time);
                    $start_time = Carbon::parse($item->start_time);
                    $hours_worked = $end_time->diffInSeconds($start_time) / 3600;
                    if ($item->type === 'take-from-overdue') {
                        // Subtract hours for take-from-overdue type
                        return 0;
                    } else {
                        // Add hours for other types
                        return $hours_worked;
                    }
                }
            });

            $current_worked_hours = $month_worked_hours - $supposed_work_hours;
        } else {
            $old_worked_hours = 0;
            $current_worked_hours = 0;
        }

        /** Get holiday data */
        $total_annual_leaves = 0;
        $total_annual_leaves_availed = 0;
        if ($profile_data) {
            $getLeaveInfo = LeaveHelper::getLeaveInfo($profile_data->id);
            $total_annual_leaves = $getLeaveInfo['annualLeaves'] + $getLeaveInfo['additionalLeaves'] + $getLeaveInfo['previousYearRemainingLeaves'];
            //        $total_annual_leaves = !empty($profile_data->jobData) ? $profile_data->jobData->total_annual_leaves : 0;
            //        $total_annual_leaves_availed = TimeTrackerUserData::where([
            ////            ['monthly_start_date', '>=', Carbon::parse($request->date)->startOfYear()->toDateString()],
            ////            ['monthly_end_date', '<=', Carbon::parse($request->date)->endOfYear()->toDateString()],
            ////            ['user_id', '=', $user_id]
            ////        ])->get()->sum('total_holidays_taken');
            $total_annual_leaves_availed = $getLeaveInfo['totalLeavesTaken'];
        }

        $holiday_model = EmployeeVacation::where([
            'user_id' => $user_id,
            ['start_date', '>=', Carbon::parse($request->date)->startOfYear()->toDateString()],
            ['end_date', '<=', Carbon::parse($request->date)->endOfYear()->toDateString()],
        ])->whereIn("leave_type", ["whole", "half"])
            ->where("is_paid", "=", 1)
            ->get();

        $user_month_planned_days = 0;

        foreach ($holiday_model as $vacation) {
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
                $user_month_planned_days += ($vacation->leave_type == "whole" ? $vacation->required_vacation_days : 0.5);
            }
        }


        /** Get user working hours */
        //Get current day working hours
        $government_current_day_seconds = $government_model->where('date', $request->date)->whereNotIn("type", ["break", "take-from-overdue"])->sum(function ($item) {
            if (isset($item->start_time) && isset($item->end_time)) {
                $end_time = Carbon::parse($item->end_time);
                $start_time = Carbon::parse($item->start_time);
                return $end_time->diffInSeconds($start_time);
            }
        });

        $government_current_day_hours = TimeTrackerHelper::getTimeTrackedForHumansAttribute($government_current_day_seconds);

        $company_current_day_hours = $company_model->where('date', $request->date)->sum(function ($item) {
            return $item->time;
        });

        //Get current week working hours
        $government_current_week_seconds = $government_model->whereBetween('date', [$current_week_first_day, $current_week_last_day])
            ->whereNotIn("type", ["break", "take-from-overdue"])
            ->sum(function ($item) {
                if (isset($item->start_time) && isset($item->end_time)) {
                    $end_time = Carbon::parse($item->end_time);
                    $start_time = Carbon::parse($item->start_time);
                    return $end_time->diffInSeconds($start_time);
                }
            });

        $government_current_week_hours
            = TimeTrackerHelper::getTimeTrackedForHumansAttribute($government_current_week_seconds);

        $company_current_week_hours = $company_model->whereBetween('date', [$current_week_first_day, $current_week_last_day])
            ->whereNotIn("type", ["break", "take-from-overdue"])
            ->sum(function ($item) {
                return $item->time;
            });
        //Get current month working hours
        $government_current_monthly_seconds = $government_model->whereBetween('date', [$current_month_first_day, $current_month_last_day])
            ->whereNotIn("type", ["break", "take-from-overdue"])
            ->sum(function ($item) {
                if (isset($item->start_time) && isset($item->end_time)) {
                    $end_time = Carbon::parse($item->end_time);
                    $start_time = Carbon::parse($item->start_time);
                    return $end_time->diffInSeconds($start_time);
                }
            });

        $government_current_monthly_hours =
            TimeTrackerHelper::getTimeTrackedForHumansAttribute($government_current_monthly_seconds);

        $company_current_monthly_hours = $company_model->whereBetween('date', [$current_month_first_day, $current_month_last_day])
            ->sum(function ($item) {
                return $item->time;
            });

        $employee_vacation_on_date = EmployeeVacation::where("start_date", $request->date)->where('user_id', $user_id)->first();

        $hoursSpecifiedOnTheDay = 0;

        $get_current_day = strtolower(Carbon::parse($request->date)->shortDayName);
        $user_hours_on_a_day = isset($profile_data->jobData->workingHours) ? $profile_data->jobData->workingHours
            ->first(function ($item) use ($get_current_day) {
                return strtolower($item->day) == $get_current_day;
            })?->working_hours ?? 0 : 0;
        if ($employee_vacation_on_date?->leave_type == "half") {
            $hoursSpecifiedOnTheDay = $user_hours_on_a_day / 2;
        } else {
            $hoursSpecifiedOnTheDay = $user_hours_on_a_day;
        }

        // calculate the overtime for the month uptill the $request date based on profile_data and user_id of the auth user
        $overtime = self::overtimeForTheMonth(Carbon::parse($request->date), $profile_data, $user_id);

        $target_value_data = self::getTargetValueInfo(Carbon::parse($request->date), $profile_data, $user_id);

        $total_worked_hours = TimeTrackerGovernment::whereNotIn('type', ['break', 'take-from-overdue'])
            ->where('date', '<=', Carbon::parse($request->date))
            ->where('date', '>=', Carbon::parse($profile_data->jobData?->accounting_date)) //calculate total worked hours from accounting date
            ->where('user_id', $user_id)
            ->select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))/3600) AS total_hours'))
            ->first()
            ->total_hours;

        $overdue_hours = TimeTrackerGovernment::where('type', 'take-from-overdue')
            ->where('user_id', $user_id)
            ->where('date', '<=', Carbon::parse($request->date))
            ->select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))/3600) AS total_hours'))
            ->first()
            ->total_hours ?? 0;

        $expected_worked_hours = self::getExpectedWorkingHours($profile_data, $request);

        //calculate hours assigned on the current day
        return response()->json([
            'timeTrackerGovernment' => $government_data,
            'timeTrackerCompany' => $company_data,

            'totalWorkedHours' => $total_worked_hours,
            'expectedWorkedHours' => $expected_worked_hours,

            'oldWorkedHours' => $old_worked_hours,
            'tillNowWorkedHours' => $current_worked_hours,
            'governmentCurrentDayWorkedHours' => $government_current_day_hours,
            'governmentCurrentWeekWorkedHours' => $government_current_week_hours,
            'governmentCurrentMonthWorkedHours' => $government_current_monthly_hours,
            'invoiceCurrentDayWorkedHours' => TimeTrackerHelper::getTimeTrackedForHumansAttribute($company_current_day_hours * 3600),
            'invoiceCurrentWeekWorkedHours' => TimeTrackerHelper::getTimeTrackedForHumansAttribute($company_current_week_hours * 3600),
            'invoiceCurrentMonthWorkedHours' => TimeTrackerHelper::getTimeTrackedForHumansAttribute($company_current_monthly_hours * 3600),
            'totalAnnualLeaves' => $total_annual_leaves,
            'totalAnnualLeavesAvailed' => $total_annual_leaves_availed,
            'totalMonthlyAnnualLeavesPlanned' => $user_month_planned_days,
            'hoursSpecifiedOnTheDay' => $hoursSpecifiedOnTheDay,
            'hoursSpecificOnTheWeek' => isset($profile_data->jobData) ? $profile_data->jobData->workingHours->sum('working_hours') : 0,
            'hoursSpecificOnTheMonth' => $this->getSupposedHoursWorked($current_month_first_day, Carbon::parse($request->date)->endOfMonth(), isset($profile_data->jobData->workingHours) ? $profile_data->jobData->workingHours : null) ?? 0,
            'currentMonthOvertime' => $overtime,
            'targetValueData' => $target_value_data,
            'overtime' => floatval($total_worked_hours) - floatval($expected_worked_hours)
        ]);
    }

    /**
     * calculates and returns overtime
     * @param Request $request
     * @return integer
     */
    public function getOvertime(Request $request)
    {
        $user_id = $request->userId;
        if (!$user_id) {
            $user_id = $this->getAuthUserId($request);
        }
        $profile_data = UserProfileData::where('user_id', $user_id)->first();

        $total_worked_hours = TimeTrackerGovernment::whereNotIn('type', ['break', 'take-from-overdue'])
            ->where('date', '<=', Carbon::parse($request->date))
            ->where('date', '>=', Carbon::parse($profile_data->jobData?->accounting_date)) //calculate total worked hours from accounting date
            ->where('user_id', $user_id)
            ->select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))/3600) AS total_hours'))
            ->first()
            ->total_hours;

        $expected_worked_hours = self::getExpectedWorkingHours($profile_data, $request);

        $overdue_hours = TimeTrackerGovernment::where('type', 'take-from-overdue')
            ->where('user_id', $user_id)
            ->where('date', '<=', Carbon::parse($request->date))
            ->select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))/3600) AS total_hours'))
            ->first()
            ->total_hours ?? 0;

        return response()->json(['overtime' => floatval($total_worked_hours) - floatval($expected_worked_hours)]);
    }

    private static function getExpectedWorkingHours($profile_data, $request)
    {
        $accounting_date = $profile_data->jobData->accounting_date;
        $expected_worked_hours = 0;
        if (!empty($accounting_date)) {
            $from_date = Carbon::parse($accounting_date);
            $till_date =  Carbon::parse($request->date);
            $till_date->setTime(23, 59, 59); // set the till date to day end time to get proper difference
            $user_working_hours_collection = $profile_data->jobData->workingHours;

            // Initialize a collection to store the counts
            $worked_hours_collection = collect();

            $day_count = 0;
            // time should only be calculated if the date is greater than or equal to accounting date
            if ($till_date->gte($from_date)) {
                // Iterate over each day in your provided collection
                foreach ($user_working_hours_collection as $item) {
                    // Get the day of the week (e.g., "mon", "tue")
                    $day_of_week = strtolower($item->day);

                    // Calculate the total count for that day prior to the ending date
                    if ($item->day == "mon") {
                        $day_count = $from_date->diffInDaysFiltered(function ($date) {
                            return $date->isMonday();
                        }, $till_date);
                    } elseif ($item->day == "tue") {
                        $day_count = $from_date->diffInDaysFiltered(function ($date) {
                            return $date->isTuesday();
                        }, $till_date);
                    } elseif ($item->day == "wed") {
                        $day_count = $from_date->diffInDaysFiltered(function ($date) {
                            return $date->isWednesday();
                        }, $till_date);
                    } elseif ($item->day == "thu") {
                        $day_count = $from_date->diffInDaysFiltered(function ($date) {
                            return $date->isThursday();
                        }, $till_date);
                    } elseif ($item->day == "fri") {
                        $day_count = $from_date->diffInDaysFiltered(function ($date) {
                            return $date->isFriday();
                        }, $till_date);
                    } elseif ($item->day == "sat") {
                        $day_count = $from_date->diffInDaysFiltered(function ($date) {
                            return $date->isSaturday();
                        }, $till_date);
                    } elseif ($item->day == "sun") {
                        $day_count = $from_date->diffInDaysFiltered(function ($date) {
                            return $date->isSunday();
                        }, $till_date);
                    }

                    $total_count = $day_count * $item->working_hours;

                    // Add the total count to the collection
                    $worked_hours_collection->put($day_of_week, $total_count);
                }
            }

            // Sum up the total counts
            $expected_worked_hours = $worked_hours_collection->sum();
        }

        return $expected_worked_hours;
    }

    /**
     * calculates the overtime for the month uptill the $date
     * @param date $date
     * @param UserProfileData $profile_data
     * @param string $user_id
     * @return float $overtime
     */
    private static function overtimeForTheMonth($date, $profile_data, $user_id)
    {
        // set the current to the first date of the month from $date
        $current = Carbon::parse($date)->firstOfMonth();
        // keeps the sum of overtime
        $overtime = 0;

        // loop from the first day of the month to the $date day
        for ($i = 1; $i <= $date->day; $i++) {
            // get the $current date to short day name e.g 'mon', 'tue' etc.
            $get_current_day = strtolower($current->shortDayName);
            // based on the current day short name, get the working hours for the given day from $user_profile jobData
            $working_hours_for_the_day = isset($profile_data->jobData->workingHours) ? $profile_data->jobData->workingHours
                ->first(function ($item) use ($get_current_day) {
                    return strtolower($item->day) == $get_current_day;
                })?->working_hours : 0;
            // if the working hours are set for the given day then proceed
            //if ($working_hours_for_the_day)
            {
                // calculate the total worked hours of TimeTrackerGovernment for the $current date for the $user_id
                $worked_hours = self::totalWorkedHoursOnADay($current, $user_id);

                // calculate the difference of worked hours from the working hours for the $current day
                $diff = $worked_hours - $working_hours_for_the_day;

                // if the $diff is greater than 0 then this means that the user has worked overtime on the $current day
                //if ($diff > 0)
                {
                    // add the $diff to overtime
                    $overtime += $diff;
                }
            }
            // change $current to the next day
            $current->addDay();
        }

        // return overtime
        return $overtime;
    }

    /**
     * traverses over the TimeTrackerGovernment entries on the given $date and for the $user_id and calculates the worked hours
     * @param date $date
     * @param string $user_id
     * @return float $worked_hours
     */
    private static function totalWorkedHoursOnADay($date, $user_id)
    {
        // keeps tracker of the sum of worked hours
        $worked_hours = 0;
        // get the TimeTrackerGovernment on the $date for the given $user_id and of the type 'task', 'ticket', 'meeting', 'internal', 'training', 'sales-presales', 'newEntry', 'ams'
        $records = TimeTrackerGovernment::whereDate('date', $date)->whereIn('type', ['task', 'ticket', 'meeting', 'internal', 'training', 'sales-presales', 'newEntry', 'ams', 'travel-expense', 'public-holiday', 'illness', 'vacation'])->where('user_id', $user_id)->get();
        // loop over the $records and add the difference of start_time and end_time of the $record to the $worked_hours
        foreach ($records as $record) {
            $worked_hours += Carbon::parse($record->start_time)->diffInMinutes(Carbon::parse($record->end_time));
        }
        // return the $worked_hours
        return $worked_hours / 60;
    }

    /**
     * traverses over the TimeTrackerGovernment entries on the given $date and for the $user_id and calculates the worked hours
     * @param date $date
     * @param string $user_id
     * @param UserProfileData $profile_data
     */
    private static function getTargetValueInfo($date, $profile_data, $user_id)
    {
        $current_month_first_day = $date->startOfMonth()->toDateString();
        $current_month_last_day = $date->endOfMonth()->toDateString();
        $difference_in_days = Carbon::parse($current_month_first_day)->diffInDays($current_month_last_day) + 1;
        $total_company_hours_data = TimeTrackerCompany::where('user_id', $user_id)
            ->whereBetween('date', [$current_month_first_day, $current_month_last_day])->get();

        // Customer time = Invoice time - good will
        $customer_time = $total_company_hours_data->where("is_goodwill", 0)->sum('time');
        $compensation_bonuses = $profile_data->userCompensationData?->bonuses?->where('target_type', 'consulting_individual_value') ?? collect([]);
        $target_value_month = $compensation_bonuses->sum('month');

        $workingHours = $profile_data->jobData?->workingHours ?? [];
        $total_working_days = 21;
        $consultingDashboard = new ConsultingDashboardController();
        if ($target_value_month) {
            $total_working_days = $consultingDashboard->calculate_work_days_in_month($workingHours, $current_month_first_day);
        }
        $date_difference_data = $consultingDashboard->calculate_work_days_in_month($workingHours, $current_month_first_day, $current_month_last_day, true);
        $target_value = $customer_time > 0
            && $difference_in_days > 0
            && $target_value_month > 0
            ? $customer_time / 8 / $target_value_month * $total_working_days / $date_difference_data["total_work_days"] * 100
            : 0;

        $target_value_absolute = $compensation_bonuses->map(function ($item) use ($customer_time) {
            return [
                'level' => $item->level,
                'absoluteValue' => number_format($item->month > 0 ? $customer_time / 8 / ($item->month) * 100.0 : 0, 2),
                'month' => $item->month,
            ];
        })->toArray();

        return [
            'targetValue' => number_format($target_value, 2),
            'targetValueAbsolute' => $target_value_absolute,
        ];
    }

    /**
     * Save tracking records for the user.
     *
     * @param $request
     */
    public function saveTimeTrackerUserData($request)
    {
        $user_id = $request->userId;
        $month_first_day = Carbon::parse($request->date)->startOfMonth()->toDateString();   //Get first day of the selected date month
        $month_last_day = Carbon::parse($request->date)->endOfMonth()->toDateString();   //Get last day of the selected date month

        //Get dataset of all worked hour records
        $month_hours_model = TimeTrackerGovernment::where('user_id', $user_id)
            ->where("type", "!=", "break")
            ->whereBetween('date', [$month_first_day, $month_last_day])->get();
        //Get the total worked hours for the given month
        $month_worked_hours = $month_hours_model->sum(function ($item) {
            if (isset($item->start_time) && isset($item->end_time)) {
                $end_time = Carbon::parse($item->end_time);
                $start_time = Carbon::parse($item->start_time);
                $hours_worked = $end_time->diffInSeconds($start_time) / 3600;
                if ($item->type === 'take-from-overdue') {
                    // Subtract hours for take-from-overdue type
                    return 0;
                } else {
                    // Add hours for other types
                    return $hours_worked;
                }
            }
        });
        $model = TimeTrackerUserData::firstOrNew([
            'monthly_start_date' => $month_first_day,
            'monthly_end_date' => $month_last_day,
            'user_id' => $user_id
        ]);

        $total_working_days = 0;
        if (!isset($model->id)) {
            //Get User job data
            $profile_data = UserProfileData::where('user_id', $user_id)->first();
            if (!isset($profile_data->jobData->workingHours))
                return ['error' => true, 'message' => 'Job data for the current user does not exist. Please create it first'];
            $user_working_days = $profile_data->jobData->workingHours;
            foreach ($user_working_days as $item) {
                if ($item->day == "mon") {
                    $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                        return $date->isMonday();
                    }, Carbon::parse($request->date));
                } elseif ($item->day == "tue") {
                    $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                        return $date->isTuesday();
                    }, Carbon::parse($request->date));
                } elseif ($item->day == "wed") {
                    $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                        return $date->isWednesday();
                    }, Carbon::parse($request->date));
                } elseif ($item->day == "thu") {
                    $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                        return $date->isThursday();
                    }, Carbon::parse($request->date));
                } elseif ($item->day == "fri") {
                    $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                        return $date->isFriday();
                    }, Carbon::parse($request->date));
                } elseif ($item->day == "sat") {
                    $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                        return $date->isSaturday();
                    }, Carbon::parse($request->date));
                } elseif ($item->day == "sun") {
                    $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                        return $date->isSunday();
                    }, Carbon::parse($request->date));
                }
            }
            $model->monthly_start_date = $month_first_day;
            $model->monthly_end_date = $month_last_day;
            $model->actual_working_days = $total_working_days;
            $model->user_id = $user_id;
        }

        //Get dataset of all vacations
        $holiday_model =
            EmployeeVacation::where('user_id', $user_id)
            ->where("is_paid", "=", 1)
            ->whereBetween('start_date', [$month_first_day, $request->date])
            ->whereBetween('end_date', [$month_first_day, $request->date])
            ->whereIn("leave_type", ["whole", "half"])
            ->get();

        $approved = 0;

        foreach ($holiday_model as $vacation) {
            $approvers = EmployeeVacationApprover::where('employee_vacation_id', $vacation->id)->get();
            $reject_count = count($approvers->where('status', 'rejected'));
            $approve_count = count($approvers->where('status', 'approved'));
            $status = "pending";
            if ($reject_count > 0) {
                $status = 'rejected';
            } elseif ($approve_count == count($approvers ?? [])) {
                $status = 'approved';
            }
            if ($status == "approved") {
                $approved += ($vacation->leave_type == "whole" ? $vacation->required_vacation_days : 0.5);
            }
        }

        $model->total_holidays_taken = $approved;
        $model->actual_worked_hours = $month_worked_hours;
        $model->save();
    }

    /**
     * get worked hours
     * @param object $system
     * @param  $start_date
     * @param  $end_date
     * @param $working_days
     * @return \Illuminate\Http\Response
     */
    private function getSupposedHoursWorked($start_date, $end_date, $working_days)
    {
        $supposed_work_hours = 0;
        if (isset($working_days)) {
            foreach ($working_days as $item) {
                $supposed_work_hours = $supposed_work_hours +
                    count(CarbonPeriod::between($start_date, $end_date)
                        ->filter(function ($date) use ($item) {
                            return strtolower($date->format('D')) == $item->day;
                        })
                        ->toArray()) * $item->working_hours;
            }
        }
        return $supposed_work_hours;
    }


    /**
     * check validations for time tracker
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function validateUserProfile(Request $request)
    {
        $request->validate(["date" => "required", "userId" => "required"]);
        $user_id = $request->userId;
        $profile_data = UserProfileData::where('user_id', $user_id)->first();
        if (!$profile_data) {
            return response()->json([
                'success' => false, 'message' => "No User Profile Exists for this user.",
            ], 422);
        }
        if (!isset($profile_data->jobData->accounting_date)) {
            return response()->json([
                'success' => false, 'message' => "Please set the accounting date first, click the link below and set the accouting date.",
                'link' => 'http://' . $request->getHttpHost() . '/user-profiles/' . $profile_data?->id . '/edit' . '?activeTab=job'
            ], 422);
        } else if (Carbon::parse($profile_data->jobData->accounting_date) > Carbon::parse($request->date)) {
            throw new \Exception("Can not save record as accounting date is greater");
        }
        return response()->json(['success' => true]);
    }

    /**
     * make ticket comments is_added false when removed from time tracker
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function makeTicketCommentFalse(Request $request)
    {
        $request->validate(["commentId" => "required"]);
        $ticket_comment = TicketComment::findOrFail($request->commentId);
        $ticket_comment->is_added = false;
        $ticket_comment->save();
        return response()->json(['success' => true]);
    }

    /**
     * Save single instance of time tracker data
     */
    public function saveTimeTrackerInstance(Request $request)
    {
        $request->validate([
            "date" => "required",
            "timeTrackerGovernment" => "nullable|required",
            "timeTrackerGovernment.startTime" => "required",
            "timeTrackerGovernment.hours" => [
                "required",
                "numeric",
                "min:0",
                "max:24", // ensure value before decimal point is not greater than 24
                new HoursValidationRule(), // ensure only 2 digits after decimal point with values of 25, 50, or 75
            ],
            "timeTrackerGovernment.description" => "required",
            "timeTrackerGovernment.hours" => [
                "required",
                "numeric",
                "min:0",
                "max:24", // ensure value before decimal point is not greater than 24
                new HoursValidationRule(), // ensure only 2 digits after decimal point with values of 25, 50, or 75
            ],
            'timeTrackerCompany' => 'sometimes|nullable|array',
            "timeTrackerCompany.moduleId" => "sometimes|nullable",
            "timeTrackerCompany.description" => "sometimes|required",
            "timeTrackerCompany.companyId" => "sometimes|required",
            "timeTrackerCompany.projectId" => "required_if:moduleType,==,newEntry",
            "timeTrackerCompany.isAmsBillable" => 'nullable|boolean'
        ]);
        $user_id = $request->userId;
        $profile_data = UserProfileData::where('user_id', $user_id)->first();
        if (!isset($profile_data->jobData->accounting_date)) {
            throw new \Exception("Please set the accounting date first");
        } else if (Carbon::parse($profile_data->jobData->accounting_date) > Carbon::parse($request->date)) {
            throw new \Exception("Can not save record as accounting date is greater");
        }
        if (!isset($profile_data->jobData->workingHours)) {
            throw new \Exception("Working hours does not exist for the current user");
        }
        $time_tracker_overlap = 0;
        DB::transaction(
            function () use ($request, &$time_tracker_overlap, &$profile_data) {

                $time_tracker_company = $request->input('timeTrackerCompany');
                //saving time tracker companies
                $company = new TimeTrackerCompany;
                if (!empty($time_tracker_company)) {
                    if ($time_tracker_company['type'] == 'ticket') {
                        $module_type = 'App\Models\TicketComment';
                        TicketComment::where('id', $time_tracker_company['moduleId'])->update(['is_added' => true]);
                        if ($time_tracker_company['amsId'] && !$time_tracker_company['isGoodwill']) {
                            $total_time = $time_tracker_company['hours'];
                            $ams = ApplicationManagementService::find($time_tracker_company['amsId']);

                            if (isset($time_tracker_company['totalRemainingHours']) && $time_tracker_company['totalRemainingHours'] > 0) {
                                if ($time_tracker_company['totalRemainingHours'] - $total_time >= 0) {
                                    $ams->remaining_hours = $time_tracker_company['totalRemainingHours'] - $total_time;
                                    $ams->save();
                                }
                            }
                        }
                    } elseif ($time_tracker_company['type'] == 'task') {
                        $module_type = 'App\Models\Task';
                    } elseif ($time_tracker_company['type'] == 'newEntry') {
                        $module_type = 'newEntry';
                    } elseif ($time_tracker_company['type'] == 'ams') {
                        $module_type = 'App\Models\ApplicationManagementService';
                    } elseif ($time_tracker_company['type'] == 'travel-expense') {
                        $module_type = 'App\Models\TravelExpense';
                        TravelExpense::where('id', $time_tracker_company['moduleId'])->update(['is_added' => true]);
                    } else {
                        $module_type = $time_tracker_company['type'] ?? null;
                    }
                    $company->module_type = $module_type;
                    $company->module_id = $time_tracker_company['moduleId'] ?? null;
                    $company->description = $time_tracker_company['description'];
                    $company->internal_note = $time_tracker_company['internalNote'] ?? "";
                    $company->time = $time_tracker_company['hours'];
                    $company->is_goodwill = $time_tracker_company['isGoodwill'];
                    $company->company_id = $time_tracker_company['companyId'];
                    $company->ams_id = @$time_tracker_company['amsId'];
                    $company->date = $request->date;
                    $company->user_id = $request->userId;
                    $company->project_id = @$time_tracker_company['projectId'];
                    $company->is_ams_billable = $time_tracker_company['isAmsBillable'] ?? null;
                    $company->save();

                    if ($time_tracker_company['type'] == 'task') {
                        $total_time = TimeTrackerCompany::where([
                            ["module_id", $company->module_id],
                            ["module_type", 'App\Models\Task'],
                        ])->sum("time");
                        $task = Task::find($company->module_id);
                        $task->spent_time = $total_time;
                        $task->save();
                        if ($company->is_goodwill == 1) {
                            $needed_time_with_goodwill = $task->milestone->project->needed_time_with_goodwill;
                            $task->milestone->project->needed_time_with_goodwill = $needed_time_with_goodwill + $company->time;
                            $task->milestone->project->save();
                        } else if ($company->is_goodwill == 0) {
                            $needed_time = $task->milestone->project->needed_time;
                            $task->milestone->project->needed_time = $needed_time + $company->time;
                            $task->milestone->project->save();
                        }
                    }
                    /**
                     * if the module type is 'App\Models\ApplicationManagementService' then we need to add the total logged
                     * time to the 'remaining_hours' of the ApplicationManagementService module based on module_id
                     */
                    else if ($time_tracker_company['type'] == 'ams' && !$time_tracker_company['isGoodwill']) {
                        $total_time = $company->time;
                        $ams = ApplicationManagementService::find($company->module_id);
                        if (isset($time_tracker_company['totalRemainingHours']) && $time_tracker_company['totalRemainingHours'] > 0) {
                            if ($time_tracker_company['totalRemainingHours'] - $total_time >= 0) {
                                $ams->remaining_hours = $time_tracker_company['totalRemainingHours'] - $total_time;
                                $ams->save();
                            }
                        }
                    } else if ($time_tracker_company['type'] == 'travel-expense') {
                        $travel_expense = TravelExpense::find($company->module_id);
                        // if from date and to date of travel expense do not match then we must create two entries for time tracker company
                        if (Carbon::parse($travel_expense->from_date)->ne(Carbon::parse($travel_expense->to_date))) {
                            // create time tacker company record for to date
                            $company = new TimeTrackerCompany;
                            $company->module_type = $module_type;
                            $company->module_id = $time_tracker_company['moduleId'] ?? null;
                            $company->description = 'Departure';
                            $company->internal_note = $time_tracker_company['internalNote'] ?? "";
                            $company->time = $travel_expense->to_discrepancy;
                            $company->is_goodwill = $time_tracker_company['isGoodwill'];
                            $company->company_id = $time_tracker_company['companyId'];
                            $company->date = $travel_expense->to_date;
                            $company->user_id = $request->userId;
                            $company->save();
                            // get the latest time tracker government entry on the to date
                            $to_date_time_tracker_government = TimeTrackerGovernment::whereDate('date', $travel_expense->to_date)->orderBy('id', 'desc')->first();
                            // start_time should be set to the end_time of the latest time tracker government entry on to date or by default use "08:00:00"
                            $start_time = Carbon::parse($to_date_time_tracker_government?->end_time ?? "08:00:00");
                            // calculate end_time by adding to_discrepancy of travel expense hours to the start_time
                            $end_time = Carbon::parse($start_time)->addMinutes(floatval($travel_expense->to_discrepancy) * 60);
                            // if by adding to_discrepancy to start_time, the end_time goes over 11:59 pm then use 11:59 pm as the end_time
                            if ($end_time->day > $start_time->day) {
                                $end_time = Carbon::parse("23:59:59");
                            }
                            // log time tracker government entry for to date
                            $government = new TimeTrackerGovernment;
                            $government->type = $time_tracker_company['type'];
                            $government->description = 'Departure';
                            $government->internal_note = $time_tracker_company['internalNote'] ?? "";
                            $government->start_time = $start_time;
                            $government->end_time = $end_time;
                            $government->date = $travel_expense->to_date;
                            $government->user_id = $request->input('userId');
                            $government->time_tracker_company_id = $company->id;
                            $government->save();
                            // post process government entries for auto breaks
                            self::postprocessGovernmentEntries($government->date, $government->user_id);
                        }
                    }
                }

                // Retrieve the timeTrackerGovernment object from the request
                $time_tracker_government = $request->input('timeTrackerGovernment');

                if (isset($time_tracker_government['startTime']) && isset($time_tracker_government['endTime'])) {
                    $time_tracker_overlap = TimeTrackerGovernment::where('start_time', '<', $time_tracker_government['endTime'])
                        ->where('end_time', '>', $time_tracker_government['startTime'])
                        ->where('user_id', 'LIKE', $request->input('userId'))
                        ->where('date', $request->input('date'))->count();
                }
                //Ensure that the times are not overlapping on top of each other
                $government = null;
                if (!$time_tracker_overlap) {
                    // Save time for governments
                    $government = new TimeTrackerGovernment;
                    if ($time_tracker_government['type'] == 'take-from-overdue') {
                        $old_worked_hours = $this->getOldWorkingHours($request, $request->input('userId'));
                        if (
                            floatval($time_tracker_government['hours']) > $old_worked_hours
                            && ($old_worked_hours - $time_tracker_government['hours']) < -10
                        ) {
                            throw new \Exception('Not enough hours to take from overdue');
                        }
                    }

                    $government->type = $time_tracker_government['type'];
                    $government->description = $time_tracker_government['description'];
                    $government->internal_note = $time_tracker_government['internalNote'] ?? "";
                    $government->start_time = $time_tracker_government['startTime'];
                    $government->end_time = $time_tracker_government['endTime'];
                    $government->date = $request->input('date');
                    $government->user_id = $request->input('userId');
                    $government->time_tracker_company_id = $company->id;
                    if ($time_tracker_government['type'] === "sales-presales") {
                        $government->company_id = $time_tracker_government['customerId'] ?? "";
                    }
                    $government->save();

                    // iterate for governmate entries on this date and update where necessary
                    self::postprocessGovernmentEntries($government->date, $government->user_id);

                    //Save user time tracker data
                    $response = $this->saveTimeTrackerUserData($request);
                }

                if (isset($response['error'])) {
                    throw new \Exception($response['message']);
                }
            }
        );
        $status_code = $time_tracker_overlap ? 422 : 200;
        $message = $time_tracker_overlap ? "Time tracker data overlapping for government" : "Tracker record has been created";
        $is_success = !$time_tracker_overlap;

        return response()->json([
            'success' => $is_success,
            'message' => $message,
        ], $status_code);
    }

    /**
     * compares $time1 and $time2, if $time1 is greater than $time2 then return $time2 else return $time1
     * @param date $time1
     * @param date $time2
     * @return date
     */
    public static function validateTimes($time1, $time2)
    {
        if ($time1->greaterThan($time2)) {
            return $time2;
        }
        return $time1;
    }

    /**
     * remove time tracker government
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function removeTimeTrackerGovernment($id)
    {
        $time_tracker_government = TimeTrackerGovernment::findOrFail($id);
        $this->removeHoursWhenRecordDeleted($time_tracker_government);
        $date = $time_tracker_government->date;
        $user_id = $time_tracker_government->user_id;
        $time_tracker_government->delete();

        // iterate for governmate entries on this date and update where necessary
        self::postprocessGovernmentEntries($date, $user_id);

        return response()->json([
            'success' => true,
            'message' => "Tracker record has been deleted",
        ]);
    }

    private static function addMinutes($time, $min)
    {
        return self::validateTimes(Carbon::parse($time)->addMinutes($min), Carbon::parse("23:59:59"));
    }

    /**
     * loops over all entries, adds/deletes breaks where necessary and splits tasks where necessary
     * @param date $date
     * @param string $user_id
     */
    public static function postprocessGovernmentEntries($date, $user_id)
    {
        DB::transaction(function () use ($date, $user_id) {
            // all the entries on this $date ordered by start_time in ascending order
            $time_tracker_entries = TimeTrackerGovernment::where('date', $date)->where('user_id', $user_id)->orderBy('start_time', 'asc')->get();
            // keeps track of number of hours for entries of type => "task", "ticket", "meeting, "training", "sales-presales", "newEntry", "ams" or "internal
            // all the entries on this $date ordered by start_time in ascending order
            $total_worked_hours = 0;
            $total_break_hours = 0;
            $total_worked_hours_before = 0;
            $last_task_end_time = false;
            $all_entries = [];
            $add_time_to_future_entries = 0;

            foreach ($time_tracker_entries as $entry) {
                $total_worked_hours_before = $total_worked_hours;
                $currentEntryHours = self::calculateHoursFromTime($entry->start_time, $entry->end_time);

                if (
                    $entry->type == "task" ||
                    $entry->type == "ticket" ||
                    $entry->type == "meeting" ||
                    $entry->type == "internal" ||
                    $entry->type == "training" ||
                    $entry->type == "sales-presales" ||
                    $entry->type == "newEntry" ||
                    $entry->type == "ams" ||
                    $entry->type == "travel-expense"
                ) {
                    $total_worked_hours += $currentEntryHours;
                } elseif ($entry->type == "break") {
                    if ($entry->is_deletable == 0 && ($total_worked_hours < 6 || $total_break_hours > 0.75 || ($total_worked_hours > 6 && $total_worked_hours <= 8 && $total_break_hours > 0.499))) {
                        $entry->delete();
                        continue;
                    }

                    if ($last_task_end_time === false) {
                        continue;
                    }

                    $total_break_hours += $currentEntryHours;
                }

                $entry->start_time = self::addMinutes($entry->start_time, 60 * $add_time_to_future_entries);
                $entry->end_time = self::addMinutes($entry->end_time, 60 * $add_time_to_future_entries);

                if ($last_task_end_time !== false) {
                    $empty_time = self::calculateHoursFromTime($last_task_end_time, $entry->start_time);
                    $total_break_hours += $empty_time;

                    /*
                    if($empty_time>0)
                    {
                        $all_entries[]=TimeTrackerGovernment::create([
                            "type" => "break",
                            "description" => "break",
                            "start_time" => $last_task_end_time,
                            "end_time" => $entry->start_time,
                            "date" => $date,
                            "is_deletable" => 0,
                            "user_id" => $entry->user_id
                        ]);
                    }
                    */
                }

                $all_entries[] = $entry;

                if ($total_worked_hours > 6 && $total_break_hours < 0.5) {
                    array_pop($all_entries);
                    $break_time_needed = (0.5 - $total_break_hours);
                    $add_time_to_future_entries += $break_time_needed;

                    if ($total_worked_hours_before != 6) {
                        $hoursLeftUntilSplit = (6 - $total_worked_hours_before);
                        $entry->end_time = self::addMinutes($entry->start_time, 60 * $hoursLeftUntilSplit);
                        $entry->save();

                        $all_entries[] = $entry;

                        $total_worked_hours_before += self::calculateHoursFromTime(end($all_entries)->start_time, end($all_entries)->end_time);

                        $all_entries[] = TimeTrackerGovernment::create([
                            "type" => "break",
                            "description" => "break",
                            "start_time" => end($all_entries)->end_time,
                            "end_time" => self::addMinutes(end($all_entries)->end_time, $break_time_needed * 60),
                            "date" => $date,
                            "is_deletable" => 0,
                            "user_id" => $entry->user_id
                        ]);

                        $all_entries[] = TimeTrackerGovernment::create([
                            "type" => $entry->type,
                            "description" => $entry->description,
                            "start_time" => end($all_entries)->end_time,
                            "end_time" => self::addMinutes(end($all_entries)->end_time, ($currentEntryHours - $hoursLeftUntilSplit) * 60),
                            "date" => $date,
                            "is_deletable" => 1,
                            "time_tracker_company_id" => $entry->time_tracker_company_id,
                            "internal_note" => $entry->internal_note,
                            "user_id" => $entry->user_id
                        ]);
                    } else {
                        $all_entries[] = TimeTrackerGovernment::create([
                            "type" => "break",
                            "description" => "break",
                            "start_time" => end($all_entries)->end_time,
                            "end_time" => self::addMinutes(end($all_entries)->end_time, $break_time_needed * 60),
                            "date" => $date,
                            "is_deletable" => 0,
                            "user_id" => $entry->user_id
                        ]);

                        $entry->start_time = self::addMinutes($entry->start_time, 60 * $add_time_to_future_entries);
                        $entry->end_time = self::addMinutes($entry->end_time, 60 * $add_time_to_future_entries);
                        $entry->save();

                        $all_entries[] = $entry;
                    }

                    $currentEntryHours = self::calculateHoursFromTime(end($all_entries)->start_time, end($all_entries)->end_time);
                    $total_break_hours += $break_time_needed;
                    $entry = end($all_entries);
                }

                if ($total_worked_hours > 8 && $total_break_hours < 0.75) {
                    array_pop($all_entries);
                    $break_time_needed = (0.75 - $total_break_hours);
                    $add_time_to_future_entries += $break_time_needed;

                    if ($total_worked_hours_before != 8) {
                        $hoursLeftUntilSplit = (8 - $total_worked_hours_before);

                        $entry->end_time = self::addMinutes($entry->start_time, 60 * $hoursLeftUntilSplit);
                        $entry->save();

                        $all_entries[] = $entry;

                        $total_worked_hours_before += self::calculateHoursFromTime(end($all_entries)->start_time, end($all_entries)->end_time);

                        $all_entries[] = TimeTrackerGovernment::create([
                            "type" => "break",
                            "description" => "break",
                            "start_time" => end($all_entries)->end_time,
                            "end_time" => self::addMinutes(end($all_entries)->end_time, $break_time_needed * 60),
                            "date" => $date,
                            "is_deletable" => 0,
                            "user_id" => $entry->user_id
                        ]);

                        $all_entries[] = TimeTrackerGovernment::create([
                            "type" => $entry->type,
                            "description" => $entry->description,
                            "start_time" => end($all_entries)->end_time,
                            "end_time" => self::addMinutes(end($all_entries)->end_time, ($currentEntryHours - $hoursLeftUntilSplit) * 60),
                            "date" => $date,
                            "is_deletable" => 1,
                            "time_tracker_company_id" => $entry->time_tracker_company_id,
                            "internal_note" => $entry->internal_note,
                            "user_id" => $entry->user_id
                        ]);
                    } else {
                        $all_entries[] = TimeTrackerGovernment::create([
                            "type" => "break",
                            "description" => "break",
                            "start_time" => end($all_entries)->end_time,
                            "end_time" => self::addMinutes(end($all_entries)->end_time, $break_time_needed * 60),
                            "date" => $date,
                            "is_deletable" => 0,
                            "user_id" => $entry->user_id
                        ]);

                        $entry->start_time = self::addMinutes($entry->start_time, 60 * $add_time_to_future_entries);
                        $entry->end_time = self::addMinutes($entry->end_time, 60 * $add_time_to_future_entries);
                        $entry->save();

                        $all_entries[] = $entry;
                    }

                    $total_break_hours += $break_time_needed;
                }

                $last_task_end_time = end($all_entries)->end_time;
            }
        });
    }


    /**
     * remove time tracker company
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function removeTimeTrackerCompany($id)
    {
        DB::transaction(function () use ($id) {
            $time_tracker_company = TimeTrackerCompany::findOrFail($id);
            if ($time_tracker_company->module_type == 'App\Models\TicketComment') {
                $this->changeTicketCommentStatusOnRemove($time_tracker_company);
                if ($time_tracker_company->ams_id) {
                    if (!$time_tracker_company->is_ams_billable) {
                        $ams = ApplicationManagementService::find($time_tracker_company->ams_id);
                        $ams->remaining_hours = $time_tracker_company->time + $ams->remaining_hours;
                        $ams->save();
                    }
                }
            } else if ($time_tracker_company->module_type == 'App\Models\TravelExpense') {
                $this->changeTravelExpenseStatusOnRemove($time_tracker_company);
            }
            $time_tracker_company->delete();
            if ($time_tracker_company->module_type == 'App\Models\Task') {
                $total_time = TimeTrackerCompany::where([
                    ["module_id", $time_tracker_company->module_id],
                    ["module_type", 'App\Models\Task'],
                ])->sum("time");
                $task = Task::find($time_tracker_company->module_id);
                $task->spent_time = $total_time;
                $task->save();
                if ($time_tracker_company->is_goodwill == 1) {
                    $needed_time_with_goodwill = $task->milestone->project->needed_time_with_goodwill;
                    $task->milestone->project->needed_time_with_goodwill = $needed_time_with_goodwill - $time_tracker_company->time;
                    $task->milestone->project->save();
                } else if ($time_tracker_company->is_goodwill == 0) {
                    $needed_time = $task->milestone->project->needed_time;
                    $task->milestone->project->needed_time = $needed_time - $time_tracker_company->time;
                    $task->milestone->project->save();
                }
            } else if ($time_tracker_company->module_type == 'App\Models\ApplicationManagementService' && !$time_tracker_company->is_goodwill) {
                if (!$time_tracker_company->is_ams_billable) {
                    $total_time = $time_tracker_company->time;
                    $ams = ApplicationManagementService::find($time_tracker_company->module_id);
                    $new_remaining_hours = $ams->remaining_hours + $total_time;
                    // if after subtracting total_time from remaining_hours, the new_remaining_hours become less than 0 then
                    $ams->remaining_hours = $new_remaining_hours;
                    $ams->save();
                }
            }
        });
        return response()->json([
            'success' => true,
            'message' => "Tracker record has been deleted",
        ]);
    }

    /**
     * Edit the TimeTrackerGovernment record with the given id using the data in the given Request object.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function editTimeTrackerGovernment($id, Request $request)
    {
        // Validate the input data in the request
        $request->validate([
            "type" => "required",
            "startTime" => "required",
            "hours" => [
                "required",
                "numeric",
                "min:0",
                "max:24", // ensure value before decimal point is not greater than 24
                new HoursValidationRule(), // ensure only 2 digits after decimal point with values of 25, 50, or 75
            ],
            "endTime" => "required",
            "description" => "required"
        ]);
        // Find the TimeTrackerGovernment record with the given id
        $government = TimeTrackerGovernment::findOrFail($id);

        //checking if time tracker overlaps
        if (isset($request->startTime) && isset($request->endTime)) {
            $time_tracker_overlap = TimeTrackerGovernment::where('start_time', '<', $request->endTime)
                ->where('end_time', '>', $request->startTime)
                ->where('date', $government->date)
                ->where('user_id', 'LIKE', $request->input('userId'))
                ->where('id', '!=', $id)->count();
            if ($time_tracker_overlap > 0) {
                // Return a falied response
                return response()->json([
                    'success' => false,
                    'message' => "Time tracker data overlapping for government",
                ], 422);
            }
        }

        // Calculate the previous hours worked if both start_time and end_time are set
        $previous_hours = 0;
        if (isset($government->start_time) && isset($government->end_time)) {
            $previous_hours = $government->type == "break" ? 0 : self::calculateHoursFromTime($government->start_time, $government->end_time);
        }

        // Update the TimeTrackerGovernment record with the data from the request
        $government->start_time = $request->startTime;
        $government->end_time = $request->endTime;
        $government->description = $request->description;
        $government->type = $request->type;
        $government->save();

        // iterate for governmate entries on this date and update where necessary
        self::postprocessGovernmentEntries($government->date, $government->user_id);

        // Calculate the new hours worked and the difference from the previous hours worked
        $new_hours = $government->type == "break" ? 0 : self::calculateHoursFromTime($government->start_time, $government->end_time);
        $hours_to_add_or_subtract = $new_hours - $previous_hours;

        // Update the actual_worked_hours field in the TimeTrackerUserData record for the user and month
        $month_first_day = Carbon::parse($government->date)->startOfMonth()->toDateString();   //Get first day of the selected date month
        $month_last_day = Carbon::parse($government->date)->endOfMonth()->toDateString();   //Get last day of the selected date month
        $time_tracker_user_data = TimeTrackerUserData::where([
            ['user_id', '=', $government->user_id],
            ['monthly_start_date', '=', $month_first_day],
            ['monthly_end_date', '=', $month_last_day]
        ])->firstOrFail();
        $time_tracker_user_data->actual_worked_hours = $time_tracker_user_data->actual_worked_hours + $hours_to_add_or_subtract;
        $time_tracker_user_data->save();

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => "Tracker record has been updated",
        ]);
    }

    public function editTimeTrackerCompanies($id, Request $request)
    {
        // Validate the input data in the request
        $request->validate([
            "type" => "required",
            "moduleId" => "required_if:type,task|required_if:type,ticket",
            "hours" => [
                "required",
                "numeric",
                "min:0",
                "max:24", // ensure value before decimal point is not greater than 24
                new HoursValidationRule(), // ensure only 2 digits after decimal point with values of 25, 50, or 75
            ],
            "description" => "required",
            "companyId" => "sometimes|required",
            "projectId" => "required_if:moduleType,==,newEntry",
        ]);

        $time_tracker_companies = TimeTrackerCompany::findOrFail($id);
        if ($request->type == 'ticket') {
            $module_type = 'App\Models\TicketComment';
        } elseif ($request->type == 'task') {
            $module_type = 'App\Models\Task';
        } elseif ($request->type == 'newEntry') {
            $module_type = 'newEntry';
        } elseif ($request->type == 'ams') {
            $module_type = 'App\Models\ApplicationManagementService';
        } elseif ($request->type == 'travel-expense') {
            $module_type = 'App\Models\TravelExpense';
        } else {
            $module_type = $time_tracker_company['type'] ?? null;
        }
        $time_tracker_companies->module_type = $module_type;
        $time_tracker_companies->module_id = $request->moduleId ?? null;
        $time_tracker_companies->description = $request->description;
        $time_tracker_companies->internal_note = $request->internalNote ?? "";
        // 'previous time' is saved to so that we can remove the 'previous time' from ams remaining_hours and replace with the newly updated time
        $previous_time = $time_tracker_companies->time;
        // save old time_tracker_company_time needed for calculation later on
        $old_time_tracker_company_time = $time_tracker_companies->time;
        $time_tracker_companies->time = $request->hours;
        $time_tracker_companies->company_id = $request->companyId;
        $time_tracker_companies->ams_id = $request->amsId;
        $time_tracker_companies->project_id = $request->projectId;

        /**
         * if the module type is 'App\Models\ApplicationManagementService' then we need to subtract the previous_time from
         *  the 'remaining_hours' of the ApplicationManagementService module based and add the total_time
         */
        if ($time_tracker_companies->module_type == 'App\Models\ApplicationManagementService') {
            $total_time = $time_tracker_companies->time;
            $ams = ApplicationManagementService::find($time_tracker_companies->module_id);
            $new_remaining_hours = $ams->remaining_hours;
            if (!$time_tracker_companies->is_goodwill && $request->isGoodwill) {
                $new_remaining_hours += $previous_time;
            } else if ($time_tracker_companies->is_goodwill && !$request->isGoodwill) {
                $new_remaining_hours -= $total_time;
            } else if (!$time_tracker_companies->is_goodwill && !$request->isGoodwill) {
                $new_remaining_hours += $old_time_tracker_company_time;
                $new_remaining_hours -= $total_time;
            }
            $ams->remaining_hours = $new_remaining_hours;
            $ams->save();
        } else if ($time_tracker_companies->module_type == 'App\Models\TicketComment' && $time_tracker_companies->ams_id) {
            $total_time = $time_tracker_companies->time;
            $ams = ApplicationManagementService::find($time_tracker_companies->ams_id);
            $new_remaining_hours = $ams->remaining_hours;
            if (!$time_tracker_companies->is_goodwill && $request->isGoodwill) {
                $new_remaining_hours += $previous_time;
            } else if ($time_tracker_companies->is_goodwill && !$request->isGoodwill) {
                $new_remaining_hours -= $total_time;
            } else if (!$time_tracker_companies->is_goodwill && !$request->isGoodwill) {
                $new_remaining_hours += $old_time_tracker_company_time;
                $new_remaining_hours -= $total_time;
            }
            $ams->remaining_hours = $new_remaining_hours;
            $ams->save();
        } else if ($time_tracker_companies->module_type == 'App\Models\Task') {
            // if the time_tracker_company being edited is goodwill and is_goodwill has not been changed
            if ($time_tracker_companies->is_goodwill == 1 && $request->isGoodwill) {
                $needed_time_with_goodwill = $time_tracker_companies->module->milestone->project->needed_time_with_goodwill;
                $time_tracker_companies->module->milestone->project->needed_time_with_goodwill = ($needed_time_with_goodwill - $old_time_tracker_company_time) + $time_tracker_companies->time;
                $time_tracker_companies->module->milestone->project->save();
            }
            // if the time_tracker_company being edited is not goodwill and is_goodwill has not been changed
            else if ($time_tracker_companies->is_goodwill == 0 && !$request->isGoodwill) {
                $needed_time = $time_tracker_companies->module->milestone->project->needed_time;
                $time_tracker_companies->module->milestone->project->needed_time = ($needed_time - $old_time_tracker_company_time) + $time_tracker_companies->time;
                $time_tracker_companies->module->milestone->project->save();
            }
            // if the time_tracker_company being edited is goodwill and is_goodwill has been changed
            else if ($time_tracker_companies->is_goodwill == 1 && !$request->isGoodwill) {
                $needed_time_with_goodwill = $time_tracker_companies->module->milestone->project->needed_time_with_goodwill;
                $needed_time = $time_tracker_companies->module->milestone->project->needed_time;
                $time_tracker_companies->module->milestone->project->needed_time_with_goodwill = $needed_time_with_goodwill - $old_time_tracker_company_time;
                $time_tracker_companies->module->milestone->project->needed_time = $needed_time + $time_tracker_companies->time;
                $time_tracker_companies->module->milestone->project->save();
            }
            // if the time_tracker_company being edited is not goodwill and is_goodwill has been changed
            else if ($time_tracker_companies->is_goodwill == 0 && $request->isGoodwill) {
                $needed_time_with_goodwill = $time_tracker_companies->module->milestone->project->needed_time_with_goodwill;
                $needed_time = $time_tracker_companies->module->milestone->project->needed_time;
                $time_tracker_companies->module->milestone->project->needed_time_with_goodwill = $needed_time_with_goodwill + $time_tracker_companies->time;
                $time_tracker_companies->module->milestone->project->needed_time = $needed_time - $old_time_tracker_company_time;
                $time_tracker_companies->module->milestone->project->save();
            }
        }
        $time_tracker_companies->is_goodwill = $request->isGoodwill ?? false;
        $time_tracker_companies->save();
        // Return a success response
        return response()->json([
            'success' => true,
            'message' => "Tracker record has been updated",
        ]);
    }

    /**
     * Calculate the number of hours between the given start and end times.
     *
     * @param string $start_time
     * @param string $end_time
     * @return string
     */
    private static function calculateHoursFromTime(string $start_time, string $end_time): string
    {
        $start_time = Carbon::parse($start_time);
        $end_time = Carbon::parse($end_time);
        $hours = $end_time->diffInSeconds($start_time) / 3600;
        return $hours;
    }


    /**
     * Retrieves the TimeTrackerUserData record for the given TimeTrackerGovernment and removes the time
     * difference between the start and end hours of the latter from the actual_worked_hours of the former.
     *
     * @param TimeTrackerGovernment $time_tracker_government The TimeTrackerGovernment record to retrieve the hours to be removed from
     *
     * @return TimeTrackerUserData The updated TimeTrackerUserData record
     */
    private function removeHoursWhenRecordDeleted(TimeTrackerGovernment $time_tracker_government): TimeTrackerUserData
    {
        /**if a record is deleted we need to remove the time from it as well*/
        $month_first_day = Carbon::parse($time_tracker_government->date)->startOfMonth()->toDateString();   //Get first day of the selected date month
        $month_last_day = Carbon::parse($time_tracker_government->date)->endOfMonth()->toDateString();   //Get last day of the selected date month

        $time_tracker_user_data = TimeTrackerUserData::where([
            ['user_id', '=', $time_tracker_government->user_id],
            ['monthly_start_date', '=', $month_first_day],
            ['monthly_end_date', '=', $month_last_day]
        ])->firstOrFail();
        $start_hour = $time_tracker_government->type == "break" ? 0 : $time_tracker_government->start_time;
        $end_hour = $time_tracker_government->type == "break" ? 0 : $time_tracker_government->end_time;
        if (isset($start_hour) && isset($end_hour)) {
            // Create Carbon instances for the start and end hours
            $carbon_start = Carbon::parse($start_hour);
            $carbon_end = Carbon::parse($end_hour);
            // Calculate the time difference in seconds
            $time_diff = $carbon_end->diffInSeconds($carbon_start) / 3600;
            if ($time_tracker_government->type == 'take-from-overdue') {
                $time_tracker_user_data->actual_worked_hours = $time_tracker_user_data->actual_worked_hours + $time_diff;
            } else {
                $time_tracker_user_data->actual_worked_hours = $time_tracker_user_data->actual_worked_hours - $time_diff;
            }
            $time_tracker_user_data->save();
        }
        return $time_tracker_user_data;
        /** */
    }

    /**
     * when time tracking company is removed, when will update ticket comment is_added to false
     * @param TimeTrackerCompany $time_tracker_company The TimeTrackerCompany model
     * @return void
     */
    private function changeTicketCommentStatusOnRemove(TimeTrackerCompany $time_tracker_company)
    {
        $module = $time_tracker_company->module;
        $ticket_comment = TicketComment::findOrFail($module->id);
        $ticket_comment->is_added = false;
        $ticket_comment->save();
    }

    /**
     * when time tracking company is removed, when will update travel expense is_added to false
     * @param TimeTrackerCompany $time_tracker_company The TimeTrackerCompany model
     * @return void
     */
    private function changeTravelExpenseStatusOnRemove(TimeTrackerCompany $time_tracker_company)
    {
        // both the module entries of the travel expense must be deleted in order to set "is_added" to false
        if (TimeTrackerCompany::where('module_id', $time_tracker_company->module_id)->count() == 1) {
            $module = $time_tracker_company->module;
            $travel_expense = TravelExpense::findOrFail($module->id);
            $travel_expense->is_added = false;
            $travel_expense->save();
        }
    }

    /**
     * retrieves the time tracker stats based on the filters received in request
     * @param Request, received request
     * @return Response, response with the statistics
     */
    public function timeTrackerStatistics(Request $request)
    {
        $time_tracker_company = new TimeTrackerCompany();

        //time tracker company only filter records that have ams remaining hours zero
        $time_tracker_company = $time_tracker_company->where(function ($query) {
            $query->whereNull('module_id')->orWhereHasMorph('module', [ApplicationManagementService::class, Task::class, TicketComment::class], function ($query) {
                $query->when(
                    is_a($query->getModel(), ApplicationManagementService::class) || is_a($query->getModel(), TicketComment::class),
                    function ($query) {
                        $query->where('time_tracker_companies.is_ams_billable', true);
                    }
                );
            });
        });

        // get the records between the startDate and endDate
        $time_tracker_company = $time_tracker_company->whereBetween('time_tracker_companies.date', [$request->startDate, Carbon::parse($request->endDate . ' 23:59:59')]);

        // filter records based on module_type. Possible values App\Models\TicketComment, App\Models\Task, newEntry, App\Models\ApplicationManagementService, App\Models\TravelExpense
        if ($request->type) {
            $time_tracker_company = $time_tracker_company->where('time_tracker_companies.module_type', $request->type);
        }

        // filter records based on status. Possible values new, pr, billed
        if ($request->status) {
            $time_tracker_company = $time_tracker_company->where('time_tracker_companies.status', $request->status);
        }
        // filter records based on invoiceStatus. Possible values draft, approved, sent
        if ($request->invoiceStatus) {
            $time_tracker_company = $time_tracker_company->whereHas('performanceRecord', function ($q) use ($request) {
                $q->whereHas('invoice', function ($q) use ($request) {
                    $q->where('status', $request->invoiceStatus);
                });
            });
        }

        //filter record based on user id's
        if (!empty($request->person)) {
            $collect_person = collect($request->person);
            $time_tracker_company = $time_tracker_company->whereIn('time_tracker_companies.user_id', $collect_person->pluck('id'));
        }
        if ($request->companyId) {
            $time_tracker_company = $time_tracker_company->where('company_id', $request->companyId);
        }
        // used to get the actual module name from the model name
        $time_tracker_module_enums = [
            "App\Models\TicketComment" => "Ticket",
            "App\Models\Task" => "Task",
            "App\Models\ApplicationManagementService" => "AMS",
            "App\Models\TravelExpense" => "Travel Expense",
            "newEntry" => "New Entry"
        ];

        $per_page = $request->perPage ?? 25;

        $time_tracker_company_copy = clone $time_tracker_company; // clone the $time_tracker_company because we want the where condition below to be applied just for listing

        // filter time_tracker_company by companyId if present in the request
        if (isset($request->sortBy) && isset($request->sortOrder)) {
            $time_tracker_company_copy = $this->dashboardTimeTrackerSorting($time_tracker_company_copy, $request->sortBy, $request->sortOrder);
        }

        // paginate the time_tracker_company data
        $models = $time_tracker_company_copy->paginate($per_page)->withQueryString();

        // maps the time_tracker_company data
        $model_collect = $models->map(function ($item) use ($time_tracker_module_enums) {
            // get the username from UserProfileData model based on the 'user_id' and concat the first_name and last_name
            $username = UserProfileData::where('user_id', $item->user_id)?->selectRaw('CONCAT(first_name, " ", last_name) AS name')->first();
            return [
                "id" => $item->id,
                "date" => $item->date,
                "type" => $time_tracker_module_enums[$item->module_type],
                "moduleId" => $item->module_id,
                "userId" => $item->user_id,
                "username" => $username?->name,
                "description" => $item->description,
                'company' => $item->company->company_name,
                "quantity" => $item->time,
                "isGoodwill" => $item->is_goodwill == 1
            ];
        });

        // mapped and paginated time_tracker_company listing
        $time_tracker_listing = [
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

        // selects company_id, company_name, the sum of time from time_tracker_company time, grouped by 'company_id' and 'company_name'
        $time_tracker_statistics = $time_tracker_company->join('companies', 'time_tracker_companies.company_id', '=', 'companies.id')
            ->selectRaw('company_id, company_name, SUM(time) AS time, SUM(CASE WHEN is_goodwill = 1 THEN time ELSE 0 END) AS goodwill_time')->groupBy('company_id', 'company_name')->get();
        return response()->json([
            "statistics" => $time_tracker_statistics->map(function ($stat) {
                return [
                    'companyId' => $stat->company_id,
                    'companyName' => $stat->company_name,
                    'time' => $stat->time
                ];
            }),
            "timeTrackerListing" => $time_tracker_listing,
            "total" => $time_tracker_statistics->sum('time'),
            "goodwillTime" => $time_tracker_statistics->sum("goodwill_time"), // sum the time of time_tracker_company goodwill
        ]);
    }


    public function timeTrackerOverviewReporting(Request $request)
    {
        $date = $request->date;
        $user_id = $request->userId;

        $time_tracker_record = TimeTrackerGovernment::where('user_id', $user_id)->where('date', $date)->get();

        return response()->json([
            'data' => $time_tracker_record
        ], 200);
    }


    public function timeTrackerOverview(Request $request)
    {
        $from_date = Carbon::parse($request->fromDate)->toDateString();
        $to_date = Carbon::parse($request->toDate)->toDateString();
        $user_id = $request->userId;


        $profile_data = UserProfileData::where('user_id', $user_id)->first();

        if ($profile_data) {
            $profile_id = $profile_data->id;
            $user_job_data = UserJobData::with('workingHours')->where('user_profile_id', $profile_id)->first();
            //dd($user_job_data->workingHours);
        }


        $record = TimeTrackerGovernment::where('user_id', $user_id)->select('date', 'start_time', 'end_time')
            ->whereNotIn("type", ["break", "take-from-overdue",'illness'])
            ->whereBetween('date', [$from_date,  $to_date])->get();
        $dates = $record->pluck('date')->unique();

        $total_working_hours = [];

        foreach ($dates as $key => $item) {
            $output = $record->where('date', $item);
            $total_hours = 0;
            foreach ($output as $index => $value) {
                if (isset($value->start_time) && isset($value->end_time)) {
                    $day = Carbon::parse($item)->format('d');
                    $day_name = substr(strtolower(Carbon::parse($item)->format('l')), 0, 3);
                    $end_time = Carbon::parse($value->end_time);
                    $start_time = Carbon::parse($value->start_time);
                    $hours_worked = $end_time->diffInSeconds($start_time) / 3600;
                    $total_hours += $hours_worked;
                    $class = 'red';
                    if ($profile_data) {
                        foreach ($user_job_data->workingHours as $hours) {
                            if ($hours->day === $day_name  && ($total_hours >= $hours->working_hours)) {
                                $class = 'green';
                            }
                        }
                    }
                }
            }
            $total_working_hours[] = ['date' => $item, 'hours' => $total_hours, 'day' => $day, 'day_name' => $day_name, 'class' => $class];
        }

        return response()->json([
            'data' => $total_working_hours
        ], 200);
    }

    /**
     * caluclates and returns the monthly forecast of time_tracker_company
     * @param Request $request
     * @return array
     */
    public function timeTrackerMonthlyForecast(Request $request)
    {
        $date = Carbon::now();

        if ($request->date) {
            $date = Carbon::parse($request->date);
        }

        // contains the time tracker data for each day of the month from the $date
        $month_data_by_day = [];

        // the first day of the month from $date
        $start_of_month = clone $date->startOfMonth();

        // keeps the sum of time of time tracker company for the date multiplied by default_service_hourly_rate of company
        $total_hourly_rate_time = 0;
        // keeps the sum of time of time tracker company
        $total_time = 0;

        $working_time_until_today = 0;
        $working_time_until_today_without_0 = 0;
        $money_per_hour = 0;
        $money_until_today = 0;

        $average_working_time_day = 0;
        $working_days_counter = 1;

        $default_service_hourly_rate_per_company = [];


        // loop from the first day of the month to the last
        for ($i = 1; $i <= $date->lastOfMonth()->day; $i++) {

            $forecast = true;
            $working_time_today = 0;

            if (Carbon::now()->addHours(-17)->gte($start_of_month)) {
                // get the time tracker company data filtered by the date and 'is_goodwill' == false and sum by time and grouped by time
                $time_tracker_data = TimeTrackerCompany::groupBy('company_id')->selectRaw('company_id, SUM(time) AS time')
                    ->where('is_goodwill', 0)->whereDate('date', $start_of_month->toDateString())->get();
                // loop over the time tracker company entries for the day

                foreach ($time_tracker_data as $item) {
                    // get the default_service_hourly_rate of the company for the entry
                    if (isset($default_service_hourly_rate_per_company[$item->company_id]) == false) {
                        $company = Company::findOrFail($item->company_id);
                        $default_service_hourly_rate_per_company[$item->company_id] = ["hourly" => $company->default_service_hourly_rate ?? 0, "name" => $company->company_name, "overall" => 0];
                    }
                    $default_service_hourly_rate = $default_service_hourly_rate_per_company[$item->company_id]["hourly"];
                    // multiply the time of the entry multiplied by default_service_hourly_rate and add to total_hourly_rate_time
                    $total_hourly_rate_time += floatval($item->time) * $default_service_hourly_rate;
                    $money_until_today += floatval($item->time) * $default_service_hourly_rate;
                    $default_service_hourly_rate_per_company[$item->company_id]["overall"] += floatval($item->time) * $default_service_hourly_rate;
                    // sum the time of the entry with total_time
                    $total_time += floatval($item->time);
                    $working_time_until_today += floatval($item->time);

                    if ($default_service_hourly_rate > 0) {
                        $working_time_until_today_without_0 += floatval($item->time);
                    }



                    $working_time_today += floatval($item->time);
                    $forecast = false;
                }
                $forecast = false;
            }

            if ($forecast == true && $start_of_month->dayOfWeekIso < 6) {
                $money_per_hour = $money_until_today / ($working_time_until_today + 1);
                $total_time += $average_working_time_day;
                $total_hourly_rate_time += $average_working_time_day * $money_per_hour;
            } elseif ($start_of_month->dayOfWeekIso < 6) {

                if ($average_working_time_day == 0) {
                    $average_working_time_day = $working_time_today;
                }

                $average_working_time_day = ($average_working_time_day + $working_time_today) / 2;
                $working_days_counter += $i * 2;
            }

            $avg_money = 0;
            $count_money = 0;
            foreach ($default_service_hourly_rate_per_company as $default) {
                $avg_money += $default["hourly"];
                $count_money += 1;
            }

            if ($count_money > 0) {
                $avg_money /= $count_money;
            }


            // set the month_data_by_day for the day
            $month_data_by_day[$start_of_month->toDateString()] = [
                "totalHourlyRateTime" => intval($total_hourly_rate_time),
                "totalTime" => intval($total_time),
                "isForecast" => $forecast
            ];
            // go to the next day
            $start_of_month = $start_of_month->addDay();
        }

        return response()->json([
            'data' => $month_data_by_day,
            'companyHourlyRate' => $default_service_hourly_rate_per_company,
            'averageMoneyPerHourWeighted' => intval($money_until_today / ($working_time_until_today + 1)),
            'averageMoneyPerHourWeightedWithOut0' => intval($money_until_today / ($working_time_until_today_without_0 + 1)),
            'averageMoneyPerHourRaw' => intval($avg_money),
            'averageMoneyPerHour' => $money_until_today / ($working_time_until_today + 1)
        ], 200);
    }

    private function getPreviousOverdueTaken($userId, $date)
    {
        $previous_overdues = TimeTrackerGovernment::where(['user_id' => $userId, ['date', '<=', $date]])
            ->where('type', 'take-from-overdue')->get();
        $overTakenTime = 0;
        foreach ($previous_overdues as $overdue) {
            $fromDateTime = date('Y-m-d') . ' ' . $overdue->start_time;
            $toDateTime = date('Y-m-d') . ' ' . $overdue->end_time;
            $absenceTime = Carbon::parse($fromDateTime)->diffInMinutes(Carbon::parse($toDateTime));
            $overTakenTime += $absenceTime / 60;
        }
        return $overTakenTime;
    }

    public function exportUserTimeTrackerData(Request $request)
    {
        $request->validate([
            "date" => "required",
        ]);
        //Get user id
        $user_id = $request->userId;
        if (!$user_id) {
            $user_id = $this->getAuthUserId($request);
        }
        //Get User weekly hours
        $profile_data = UserProfileData::where('user_id', $user_id)->first();
        $accounting_date = $profile_data->jobData->accounting_date;

        $start_date = Carbon::parse($accounting_date);
        $end_date = Carbon::parse($request->date);

        $user_working_days = $profile_data->jobData->workingHours->pluck('day')->toArray();
        $selected_dates = collect();
        while ($start_date->lte($end_date)) {
            $day_to_check = strtolower($start_date->format('D'));
            $expected_hours = 0;
            if (in_array($day_to_check, $user_working_days)) {
                $expected_hours = $profile_data->jobData->workingHours->where('day', $day_to_check)->first()->working_hours;
            }
            $selected_dates->push([
                'date' => $start_date->copy()->toDateString(),
                'expectedHours' => $expected_hours
            ]);
            $start_date->addDay();
        }

        //type | description | start time | end time | Hours| Hours minus Expected
        $time_tracker_model = TimeTrackerGovernment::where([
            ['date', '<', $request->date],
            ['user_id', $user_id],
        ]);
        $time_tracker_data = $time_tracker_model->select(
            'date',
            'type',
            'description',
            'start_time',
            'end_time'
        )->get();

        $file_name_to_store = $profile_data->first_name . '-' . $profile_data->first_name . '-' . time() . '.xlsx';

        return Excel::download(new TimeTrackerExport($selected_dates, $time_tracker_data), $file_name_to_store);
    }
}
