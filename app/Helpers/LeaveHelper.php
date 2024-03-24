<?php

namespace App\Helpers;

use App\Models\EmployeeVacation;
use App\Models\GlobalSetting;
use App\Models\UserProfileData;
use Carbon\Carbon;

class LeaveHelper
{
    public static function getRemainingLeaves($model)
    {
        $previousYearRemainingLeaves = 0;
        $totalRemainingLeaves = 0;
        $additionalLeaves = 0;
        $annualLeaves = 0;
        $totalLeavesTaken = 0;
        $currentLeaveYear = $model->userJobLeave->where('leave_year', Carbon::now()->format('Y'))->first();
        if ($currentLeaveYear) {
            $annualLeaves = $currentLeaveYear->total_annual_leaves ?? 0;
            $additionalLeaves = $currentLeaveYear->additional_leaves ?? 0 ;
            $totalRemainingLeaves = ($currentLeaveYear->total_annual_leaves ?? 0) + ($currentLeaveYear->additional_leaves ?? 0) -
                $currentLeaveYear->availed_leaves ?? 0;
            $totalLeavesTaken = $currentLeaveYear->availed_leaves ?? 0;
            $expiryMonthData = GlobalSetting::where("key", "LIKE", "expiryMonth")->first() ?? 0;
            $nowMonth = Carbon::now()->month;
            if ($expiryMonthData && $nowMonth <= $expiryMonthData->value && $currentLeaveYear) {
                $subYear = Carbon::now()->subYear()->format('Y');
                $previousYear = $model->userJobLeave->where('leave_year', $subYear)->first();
                if ($previousYear) {
                    $previousYearRemainingLeaves = ($previousYear->total_annual_leaves ?? 0) + ($previousYear->additional_leaves ?? 0) -
                        $previousYear->availed_leaves ?? 0;
                    $totalRemainingLeaves += $previousYearRemainingLeaves;
                    $totalLeavesTaken += $previousYear->availed_leaves ?? 0;
                }
            }
        }
        return [
            'annualLeaves' => $annualLeaves,
            'additionalLeaves' => $additionalLeaves,
            'totalRemainingLeaves' => $totalRemainingLeaves,
            'previousYearRemainingLeaves' => $previousYearRemainingLeaves,
            'totalLeavesTaken' => $totalLeavesTaken,
        ];
    }

    public static function getLeaveInfo($userProfileId)
    {
        $previousYearRemainingLeaves = 0;
        $totalRemainingLeaves = 0;
        $additionalLeaves = 0;
        $annualLeaves = 0;
        $totalLeavesTaken = 0;
        $totalHolidaysTakenCurrent = 0;
        $userProfile = UserProfileData::with('jobData', 'jobData.userJobLeave')->find($userProfileId);
        if ($userProfile) {
            if (count($userProfile?->jobData?->userJobLeave ?? []) > 0) {
                $expiryMonthData = GlobalSetting::where("key", "LIKE", "expiryMonth")->first() ?? 0;
                $nowMonth = Carbon::now()->month;
                $userJobLeaves = $userProfile->jobData->userJobLeave;
                $currentLeaveYear = $userJobLeaves->where('leave_year', Carbon::now()->format('Y'))->first();
                if ($expiryMonthData) {
                    $subYear = Carbon::now()->subYear()->format('Y');
                    $previousYear = $userJobLeaves->where('leave_year', $subYear)->first();
                    if ($previousYear) {
                        $previousYearApproved = EmployeeVacation::where("is_special_leave", 0)->whereYear('start_date', $subYear)
                            ->whereMonth('start_date', '>', $expiryMonthData->value)
                            ->where('user_id', $userProfile->user_id)->get();
                        foreach ($previousYearApproved as $leave) {
                            if (self::isLeaveApproved($leave)) {
                                if ($leave->type == 'half') {
                                    $totalLeavesTaken += 0.5;
                                } else {
                                    $totalLeavesTaken += $leave->required_vacation_days;
                                }
                            }
                        }
                        $previousYearTotalLeaves = ($previousYear->total_annual_leaves  ?? 0) + $previousYear->additional_leaves ?? 0;
                        if ($totalLeavesTaken < $previousYearTotalLeaves) {
                            $previousYearRemainingLeaves = $previousYearTotalLeaves - $totalLeavesTaken;
                            $totalLeavesTaken = 0;
                            $currentYearApprovedBeforeExpiry = EmployeeVacation::where("is_special_leave", 0)->whereYear('start_date', Carbon::now()->format('Y'))
                                ->whereMonth('start_date', '<=', $expiryMonthData->value)
                                ->where('user_id', $userProfile->user_id)->get();
                            $daysToEndOfMonth = 0;
                            $totalHolidaysTakenCurrent = 0;
                            foreach ($currentYearApprovedBeforeExpiry as $leave) {
                                if (self::isLeaveApproved($leave)) {
                                    if ($leave->leave_type == 'half') {
                                        $totalLeavesTaken += 0.5;
                                        $totalHolidaysTakenCurrent += 0.5;
                                    } else {
                                        $starDateMonth = Carbon::parse($leave->start_date)->month;
                                        $endDateMonth = Carbon::parse($leave->end_date)->month;
                                        if (
                                            $starDateMonth == $expiryMonthData->value &&
                                            $endDateMonth > $expiryMonthData->value
                                        ) {
                                            $startDate = Carbon::parse($leave->start_date);
                                            $daysToEndOfMonth = $startDate->daysInMonth - $startDate->day + 1;
                                            $totalLeavesTaken += $daysToEndOfMonth;
                                            $totalHolidaysTakenCurrent += $daysToEndOfMonth;
                                        } else {
                                            $totalLeavesTaken += $leave->required_vacation_days;
                                            $totalHolidaysTakenCurrent += $leave->required_vacation_days;
                                        }
                                    }
                                }
                            }
                            $othersLeaves = 0;
                            if ($totalLeavesTaken > $previousYearRemainingLeaves) {
                                $othersLeaves = $totalLeavesTaken - $previousYearRemainingLeaves;
                                $previousYearRemainingLeaves = 0;
                            } else {
                                $previousYearRemainingLeaves = $previousYearRemainingLeaves - $totalLeavesTaken;
                            }
                            $currentYearApprovedWithInExpiryMonth = EmployeeVacation::where("is_special_leave", 0)->whereYear('start_date', Carbon::now()->format('Y'))
                                ->WhereMonth('start_date', $expiryMonthData->value)
                                ->WhereMonth('end_date', '>', $expiryMonthData->value)
                                ->where('user_id', $userProfile->user_id)->get();
                            $daysToStartOfMonth = 0;
                            $totalLeavesTaken = 0;
                            $currentYearTotalLeaves = ($currentLeaveYear->total_annual_leaves  ?? 0) + $currentLeaveYear->additional_leaves ?? 0;
                            foreach ($currentYearApprovedWithInExpiryMonth as $leave) {
                                if (self::isLeaveApproved($leave)) {
                                    $daysToStartOfMonth = Carbon::parse($leave->end_date)->day;
                                    $totalLeavesTaken += $daysToStartOfMonth;
                                    $totalHolidaysTakenCurrent += $daysToStartOfMonth;
                                }
                            }
                            $currentYearApproved = EmployeeVacation::where("is_special_leave", 0)->whereYear('start_date', Carbon::now()->format('Y'))
                                ->WhereMonth('start_date', '>', $expiryMonthData->value)
                                ->where('user_id', $userProfile->user_id)->get();
                            foreach ($currentYearApproved as $leave) {
                                if (self::isLeaveApproved($leave)) {
                                    if ($leave->leave_type == 'half') {
                                        $totalLeavesTaken += 0.5;
                                        $totalHolidaysTakenCurrent += 0.5;
                                    } else {
                                        $totalLeavesTaken += $leave->required_vacation_days;
                                        $totalHolidaysTakenCurrent = $leave->required_vacation_days;
                                    }
                                }
                            }
                            $currentYearRemainingLeaves = $currentYearTotalLeaves - $totalLeavesTaken - $othersLeaves;
                            $annualLeaves = ($currentLeaveYear->total_annual_leaves  ?? 0);
                            $additionalLeaves = $currentLeaveYear->additional_leaves ?? 0;
                            if ($expiryMonthData && $nowMonth <= $expiryMonthData->value) {
                                $totalRemainingLeaves = $currentYearRemainingLeaves + $previousYearRemainingLeaves;
                            } else {
                                $expiresLeaves = $previousYearRemainingLeaves;
                                $previousYearRemainingLeaves = 0;
                                $totalRemainingLeaves = $currentYearRemainingLeaves;
                                $totalHolidaysTakenCurrent = $totalLeavesTaken;
                            }
                        } else {
                            return self::getCurrentYearData($userProfile->user_id, $currentLeaveYear);
                        }
                    } else {
                        return self::getCurrentYearData($userProfile->user_id, $currentLeaveYear);
                    }
                } else {
                    return self::getCurrentYearData($userProfile->user_id, $currentLeaveYear);
                }
            }
        }
        return [
            'annualLeaves' => $annualLeaves,
            'additionalLeaves' => $additionalLeaves,
            'totalRemainingLeaves' => $totalRemainingLeaves,
            'previousYearRemainingLeaves' => $previousYearRemainingLeaves,
            'totalLeavesTaken' => @$totalHolidaysTakenCurrent,
            'expiresLeaves' => @$expiresLeaves,
        ];
    }

    private static function getCurrentYearData($userId, $currentYear)
    {
        $previousYearRemainingLeaves = 0;
        $totalRemainingLeaves = 0;
        $additionalLeaves = 0;
        $annualLeaves = 0;
        $totalLeavesTaken = 0;
        if ($currentYear) {
            $currentYearApproved = EmployeeVacation::where("is_special_leave", 0)->whereYear('start_date', Carbon::now()->format('Y'))
                ->where('user_id', $userId)->get();
            foreach ($currentYearApproved as $leave) {
                if (self::isLeaveApproved($leave)) {
                    if ($leave->leave_type == 'half') {
                        $totalLeavesTaken += 0.5;
                    } else {
                        $totalLeavesTaken += $leave->required_vacation_days;
                    }
                }
            }
            $annualLeaves = ($currentYear->total_annual_leaves  ?? 0);
            $additionalLeaves = $currentYear->additional_leaves ?? 0;
            $totalRemainingLeaves = ($annualLeaves + ($additionalLeaves)) - $totalLeavesTaken;
        }
        return [
            'annualLeaves' => $annualLeaves,
            'additionalLeaves' => $additionalLeaves,
            'totalRemainingLeaves' => $totalRemainingLeaves,
            'previousYearRemainingLeaves' => $previousYearRemainingLeaves,
            'totalLeavesTaken' => $totalLeavesTaken,
            'expiresLeaves' => 0,
        ];
    }

    /**
     * checks and returns true/false if the leave is approved
     * @param model $vacation
     * @return boolean
     */
    private static function isLeaveApproved($vacation)
    {
        $approvers = $vacation->employeeVacationApprover;
        $rejected_count = count($approvers->where('status', 'rejected'));
        if ($rejected_count > 0){
            return false;
        }
        $approve_count = count($approvers->where('status', 'approved'));
        if ($approve_count > 0){
            return true;
        }
        return false;
    }
}
