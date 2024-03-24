<?php

namespace App\Http\Controllers;

use App\Models\TimeTrackerCompany;
use App\Models\TimeTrackerGovernment;
use App\Models\UserProfileData;
use App\Models\UserTeam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use App\Traits\GetUserProfilePicture;

class ConsultingDashboardController extends Controller
{
    use GetUserProfilePicture;
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JSONResponse
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'employees' => 'nullable|array',
            'employees.*.userId' => 'required|string',
            'team' => 'nullable|string'
        ]);
        $employees_data = [];
        $employees = $request->employees;
        $user_team = null;

        if (isset($employees) && count($employees ?? []) > 0) {
            foreach ($employees as $employee) {
                $user_id = $employee['userId'];
                $profile_data = UserProfileData::where('user_id', $user_id)->first();
                $data = $this->getEmployeeData($request, $user_id, $profile_data);
                if (isset($data))
                    $employees_data[] = $data;
            }
        } else if (isset($request->team)) {
            $team_id = $request->team;
            $user_team = UserTeam::findOrFail($team_id);
            $team_working_time = 0;
            $team_customer_time = 0;
            $team_kulanz_time = 0;
            $team_internal_time = 0;
            $team_target_value = 0;
            $team_target_value_per_month = 0;
            $team_target_value_month = 0;
            $team_target_value_user = 0;
            $team_target_value_absolute = 0;
            $team_utilization = 0;
            $expected_working_time = 0;
            $employees = $user_team->teamMembers;
            foreach ($employees as $employee) {
                $user_id = $employee->user_id;
                $profile_data = UserProfileData::where('user_id', $user_id)->first();
                $data = $this->getEmployeeData($request, $user_id, $profile_data);
                // sum up the employees stats to get the team stats
                $team_working_time += isset($data['workingTime']) ? $data['workingTime'] : 0;
                $team_customer_time += isset($data['customerTime']) ? $data['customerTime'] : 0;
                $team_kulanz_time += isset($data['kulanzTime']) ? $data['kulanzTime'] : 0;
                $team_internal_time += isset($data['internalTime']) ? $data['internalTime'] : 0;
                $team_target_value += isset($data['targetValue']) ? $data['targetValue'] : 0;
                $team_target_value_per_month += isset($data['targetValuePerMonth']) ? $data['targetValuePerMonth'] : 0;
                $team_target_value_month += isset($data['targetValueMonth']) ? $data['targetValueMonth'] : 0;
                $team_target_value_absolute += isset($data['targetValueAbsolute']) ? $data['targetValueAbsolute'] : 0;
                $team_target_value_user += isset($data['targetValueUser']) ? $data['targetValueUser'] : 0;
                $team_utilization += isset($data['utilization']) ? $data['utilization'] : 0;
                $expected_working_time += isset($data['expectedWorkingTime']) ? $data['expectedWorkingTime'] : 0;

                if (isset($data))
                    $employees_data[] = $data;
            }
        }

        return response()->json(['data' => [
            "team" => $user_team ? [
                'name' => $user_team->name,
                'department' => $user_team->department?->name,
                'teamLead' => ($user_team->teamLead?->first_name ?? '') . ' ' . ($user_team->teamLead?->last_name ?? ''),
                'workingTime' => $team_working_time,
                'expectedWorkingTime' => $expected_working_time,
                'customerTime' => $team_customer_time,
                'kulanzTime' => $team_kulanz_time,
                'internalTime' => $team_internal_time,
                'targetValue' => $team_target_value_user > 0 ? number_format($team_customer_time * 1.0 / $team_target_value_user * 100.0, 2) : 0,
                'targetValuePerMonth' => $team_target_value_per_month,
                'targetValueMonth' => $team_target_value_month,
                'targetValueAbsolute' => $team_target_value_per_month > 0 ? number_format($team_customer_time / 8 / $team_target_value_per_month * 100, 2) : 0,
                'targetValueUser' => $team_target_value_user,
                'utilization' => $team_working_time > 0 ? number_format($team_customer_time * 1.0 / $team_working_time * 100.0, 2) : 0,
            ] : null,
            "employees" => $employees_data
        ]]);
    }


    /**
     * get worked hours
     * @param  $start_date
     * @param  $end_date
     * @param  $working_days
     * @return integer
     */
    private function getSupposedHoursWorkedForCurrentUser($start_date, $end_date, $working_days)
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


    private function getEmployeeData(Request $request, string $user_id, UserProfileData $profile_data): ?array
    {

        if (isset($profile_data->jobData?->workingHours) && isset($profile_data->jobData?->accounting_date)) {
            /**
             * Working Time = Final end time from time tracker
             * Total worked time for the date range without type 'public-holiday', 'illness', 'break', 'vacation'
             */
            $start_date = Carbon::parse($request->startDate)->format('Y-m-d');
            $end_date = Carbon::parse($request->endDate)->format('Y-m-d');
            $difference_in_days = Carbon::parse($start_date)->diffInDays($end_date) + 1;
            $total_working_hours_data = TimeTrackerGovernment::whereNotIn('type', [
                'public-holiday', 'illness', 'break', 'vacation'
            ])->where('user_id', $user_id)
                ->whereBetween('date', [$start_date, $end_date])
                ->get();

            $total_working_hours = $total_working_hours_data->map(function ($item) {
                $startTime = strtotime($item->start_time);
                $endTime = strtotime($item->end_time);
                return $endTime - $startTime;
            })->sum() / 3600;

            $total_company_hours_data = TimeTrackerCompany::where('user_id', $user_id)
                ->whereBetween('date', [$start_date, $end_date])->get();

            // Customer time = Invoice time - good will
            $customer_time = $total_company_hours_data->where("is_goodwill", 0)->sum('time');

            // Kulanz time = Good will
            $kulanz_time = $total_company_hours_data->where("is_goodwill", 1)->sum('time');

            // Internal time = meeting + internal + sales/presales + training
            $total_internal_time = $total_working_hours_data->whereIn("type", ['meeting', 'internal', 'sales-presales', 'training'])
                ->map(function ($item) {
                    $startTime = strtotime($item->start_time);
                    $endTime = strtotime($item->end_time);
                    return $endTime - $startTime;
                })->sum() / 3600;

            //Target value = number of days * total target value in month / 21
            $job_data = $profile_data->jobData;
            $target_value_user = 0;
            $target_value_month = $job_data->target_value_month;
            $total_working_days = 21;
            if (isset($job_data->target_value_month)) {
                $target_value_hours = $target_value_month * 8;
                $total_working_days = $this->calculate_work_days_in_month($job_data?->workingHours ?? [], $start_date);
                $target_value_user_per_day = ($target_value_hours / $total_working_days);
            }

            // Utilization = working hours / customer time in hours  / 8 * 21 / "selected date range in days"
            $utilization = $customer_time > 0 && $difference_in_days > 0 ? $customer_time / $total_working_hours * 100 : 0;

            // Target Value = customer time / 8 / "target value in days" from this employee * total working days / "selected date range in days" * 100
            $date_difference_data = $this->calculate_work_days_in_month($job_data?->workingHours ?? [], $start_date, $end_date, true);
            $target_value =  $customer_time > 0
                && $difference_in_days > 0
                && $target_value_month > 0
                ? $customer_time / 8 / $target_value_month * $total_working_days / $date_difference_data["total_work_days"] * 100
                : 0;
            $expected_working_time = $date_difference_data["total_work_hours"];
            $target_value_user = $target_value_user_per_day * ($expected_working_time / 8);

            //Get profile picture url
            $profile_pic_url = $this->getProfilePicture($profile_data->user_id ?? null) ?? null;

            return [
                'workingTime' => $total_working_hours,
                'expectedWorkingTime' => $expected_working_time,
                'customerTime' => $customer_time,
                'kulanzTime' => $kulanz_time,
                'internalTime' => $total_internal_time,
                'targetValue' => number_format($target_value, 2),
                'targetValuePerMonth' => $job_data->target_value_month,
                'targetValueUser' => number_format($target_value_user, 2),
                'targetValueAbsolute' => $target_value_month > 0 ? $customer_time / 8 / ($target_value_month) * 100.0 : 0,
                'targetValueMonth' => $target_value_month * 8,
                'utilization' => number_format($utilization, 2),
                "employee" => [
                    "name" => $profile_data->first_name . ' ' . $profile_data->last_name,
                    "jobTitle" => $profile_data->jobData?->job_title,
                    "personalNumber" => $profile_data->jobData?->personal_number,
                    "location" => $profile_data->jobData?->location?->name,
                    "profilePicture" => $profile_pic_url,
                    "userId" => $profile_data->user_id
                ]
            ];
        }
        return null;
    }



    /**
     * Calculate the number of work days in a specific month based on the given work days of the week.
     *
     * @param array $work_days_collection A collection of work days containing 'day' (e.g., 'mon', 'tue') and 'working_hours' information.
     * @param string $range_start_date The start date of our dataset.
     * @param string $range_end_date The end date of our dataset.
     * @param boolean $is_range True/false to calculate between start and end date.
     *
     */
    function calculate_work_days_in_month($work_days_collection, $range_start_date, $range_end_date = false, $is_range = false)
    {
        $current_month = Carbon::parse($range_start_date)->month;
        $current_year = Carbon::parse($range_start_date)->year;

        $work_days = [];
        foreach ($work_days_collection as $work_day) {
            $day = Carbon::parse($work_day['day'])->dayOfWeek;
            $work_days[$day] = $work_day['working_hours']; // Associate working hours with day of the week
        }

        if ($is_range == false) {
            $start_date = Carbon::create($current_year, $current_month, 1);
            $end_date = Carbon::create($current_year, $current_month, 1)->endOfMonth();
        } else {
            $start_date = $range_start_date;
            $end_date = $range_end_date;
        }

        $period = new CarbonPeriod($start_date, $end_date);

        $total_work_days = 0;
        $total_work_hours = 0;

        foreach ($period as $date) {
            $day_of_week = $date->dayOfWeek;

            if (array_key_exists($day_of_week, $work_days)) {
                $total_work_days++;
                $total_work_hours += $work_days[$day_of_week]; // Add the working hours for the day
            }
        }

        if ($is_range) {
            return ['total_work_days' => $total_work_days, 'total_work_hours' => $total_work_hours];
        } else {
            return $total_work_days;
        }
    }
}
