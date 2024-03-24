<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\EmployeeVacation;
use App\Models\EmployeeVacationApprover;
use App\Models\TimeTrackerGovernment;
use App\Models\TimeTrackerUserData;
use App\Models\UserDepartment;
use App\Models\UserLocation;
use App\Models\UserProfileData;
use App\Models\UserTeam;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProductCategorySeeder::class,
            SupplierSeeder::class,
            EloVersionSeeder::class,
            ProductGroupSeeder::class,
            ProductSeeder::class,
            CompanySeeder::class,
            ReportCategorySeeder::class,
            UserProfileDataSeeder::class,
            ProductUnitSeeder::class,
            PaymentPeriodSeeder::class,
            ProductSoftwareSeeder::class,
        ]);

        /*
        UserLocation::factory(3)->create();
        UserDepartment::factory(3)->create();
        UserTeam::factory(3)->create()->each(function ($model) {
            $user_team_member_ids = [
                "18678043-924f-54c7-0350-c636d2098967",
                "1867f9b4-983f-f721-0276-462cc3a77712",
                "18683757-971d-27c8-022d-f0a9e7f4d192",
                "18683771-0199-6c8b-026d-4bb81a4d7e51",
                "18683782-1a3f-f539-023e-97b7311e6cbc",
            ];

            shuffle($user_team_member_ids);
            $team_members = array_slice($user_team_member_ids, 0, 3);
            $team_lead_id = $model->teamLead->user_id;
            array_push($team_members, $team_lead_id);

            $model->teamMembers()->sync($team_members);
        });
        EmployeeVacation::factory(50)->create()->each(function ($model) {
            $user_data_model = UserProfileData::where("user_id", $model->user_id)
                ->with(["teams:id,team_lead_id", "teams.teamLead:id,user_id"])
                ->first();
            $team_lead_ids = $user_data_model->teams->map(function ($team) {
                return !empty($team->teamLead) ? $team->teamLead->user_id : false;
            })->toArray();
            if ($user_data_model?->jobData?->supervisor_id) {
                array_push($team_lead_ids, $user_data_model?->jobData?->supervisor_id);
            }
            $team_lead_ids = array_unique($team_lead_ids);
            if (!empty($team_lead_ids)) {
                $model->vacationApprovers()->sync($team_lead_ids);
            }
        });

        TimeTrackerGovernment::factory(200)->create()->each(function ($model) {
            $date = $model->date;
            $user_id = $model->user_id;
            $month_first_day = Carbon::parse($date)->startOfMonth()->toDateString();   //Get first day of the selected date month
            $month_last_day = Carbon::parse($date)->endOfMonth()->toDateString();   //Get last day of the selected date month

            //Get dataset of all worked hour records
            $month_hours_model = TimeTrackerGovernment::where('user_id', $user_id)
                ->whereBetween('date', [$month_first_day, $month_last_day])
                ->get();

            //Get the total worked hours for the given month
            $month_worked_hours = $month_hours_model->sum(function ($item) {
                $end_time = Carbon::parse($item->end_time);
                $start_time = Carbon::parse($item->start_time);
                return  $end_time->diffInSeconds($start_time);
            }) / 3600;

            $model_time_tracker_user = TimeTrackerUserData::firstOrNew([
                'monthly_start_date' => $month_first_day,
                'monthly_end_date' => $month_last_day,
                'user_id' => $user_id
            ]);

            $total_working_days = 0;
            if (!isset($model_time_tracker_user->id)) {
                //Get User job data
                $profile_data = UserProfileData::where('user_id', $user_id)->first();
                if (!isset($profile_data->jobData->workingHours))
                    return ['error' => true, 'message' => 'Job data for the current user does not exist. Please create it first'];
                $user_working_days = $profile_data->jobData->workingHours;
                foreach ($user_working_days as $item) {
                    if ($item->day == "mon") {
                        $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                            return $date->isMonday();
                        }, Carbon::parse($date));
                    } elseif ($item->day == "tue") {
                        $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                            return $date->isTuesday();
                        }, Carbon::parse($date));
                    } elseif ($item->day == "wed") {
                        $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                            return $date->isWednesday();
                        }, Carbon::parse($date));
                    } elseif ($item->day == "thu") {
                        $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                            return $date->isThursday();
                        }, Carbon::parse($date));
                    } elseif ($item->day == "fri") {
                        $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                            return $date->isFriday();
                        }, Carbon::parse($date));
                    } elseif ($item->day == "sat") {
                        $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                            return $date->isSaturday();
                        }, Carbon::parse($date));
                    } elseif ($item->day == "sun") {
                        $total_working_days = $total_working_days + Carbon::parse($month_first_day)->diffInDaysFiltered(function ($date) {
                            return $date->isSunday();
                        }, Carbon::parse($date));
                    }
                }
                $model_time_tracker_user->monthly_start_date = $month_first_day;
                $model_time_tracker_user->monthly_end_date = $month_last_day;
                $model_time_tracker_user->actual_working_days = $total_working_days;
                $model_time_tracker_user->user_id = $user_id;
            }


            //Get dataset of all vacations
            $model_holiday =
                EmployeeVacation::where('user_id', $user_id)
                ->where("is_paid", "=", 1)
                ->whereBetween('start_date', [$month_first_day, $date])
                ->whereBetween('end_date', [$month_first_day, $date])
                ->whereIn("leave_type", ["whole", "half"])
                ->get();

            $approved = 0;

            foreach ($model_holiday as $vacation) {
                $approvers = EmployeeVacationApprover::where('employee_vacation_id', $vacation->id)->get();
                $reject_count = count($approvers->where('status', 'rejected'));
                $approve_count = count($approvers->where('status', 'approved'));
                $status = "pending";
                if ($reject_count > 0 && $approve_count > 0) {
                    $status = 'conflict';
                } elseif ($reject_count > 0) {
                    $status = 'rejected';
                } elseif ($approve_count > 0) {
                    $status = 'approved';
                }
                if ($status == "approved") {
                    $approved += ($vacation->leave_type == "whole" ? 1 : 0.5);
                }
            }

            $model_time_tracker_user->total_holidays_taken = $approved;
            $model_time_tracker_user->actual_worked_hours = $month_worked_hours;
            $model_time_tracker_user->save();
        });
        */
    }
}
