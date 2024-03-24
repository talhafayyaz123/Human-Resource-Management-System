<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Helpers\LeaveHelper;
use App\Models\CompensationBonus;
use App\Models\DocumentAndContract;
use App\Models\FleetDriver;
use App\Models\Project;
use App\Models\UserCompensationData;
use App\Models\UserDepartment;
use App\Models\UserJobData;
use App\Models\UserJobLeave;
use App\Models\UserProfileData;
use App\Models\UserTeam;
use App\Models\UserWorkingHour;
use App\Traits\CustomHelper;
use App\Traits\GetUserProfilePicture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    use GetUserProfilePicture;
    use CustomHelper;

    /**
     * Runs when instance is initiated
     */
    public function __construct()
    {
        // $this->middleware('check.permission:user-profile.list', ['only' => ['showJobDataByUser', 'listDocumentAndContract']]); //removed permissions due to requirement  'showProfileData', 'showJobData','index', 'showCompensationData'
        $this->middleware('check.permission:user-profile.create', ['only' => ['saveProfileData', 'saveJobData', 'saveCompensationData', 'uploadDocumentAndContract']]);
        $this->middleware('check.permission:user-profile.edit', ['only' => ['saveProfileData', 'saveJobData', 'saveCompensationData', 'uploadDocumentAndContract']]);
        $this->middleware('check.permission:user-profile.delete', ['only' => ['destroy', 'deleteDocumentAndContract']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 10;
        $model = UserProfileData::orderBy('created_at');

        // if the 'isConstrained' param is set and the user is not an admin then proceed inside the if block
        if ($request->isConstrained == true && !$this->hasRole($request, 'admin')) {
            // if the user is a team lead then filter the employees based on the team members of that team
            $model = $model->whereHas('teams', function ($query) use ($request) {
                $query->whereHas('teamLead', function ($query) use ($request) {
                    $query->where('user_id', $this->getAuthUserId($request));
                });
            })->orWhere('user_id', $this->getAuthUserId($request));
        }

        // filter based on teams and consultingTeams if 'isConsulting' is true and the auth user is not an admin
        if ($request->isConsulting == 1 && !$this->hasRole($request, 'admin')) {
            $model = $model->whereHas('teams', function ($query) {
                $query->whereHas('consultingTeam');
            });
        } else if ($request->isPMConsulting == 1 && !$this->hasRole($request, 'admin')) {
            $model = $model->whereHas('teams', function ($query) {
                $query->whereHas('pmDashboardTeam');
            });
        }

        if ($request->search) {
            $model->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%' . $request->search . '%')
                ->orWhereHas('jobData', function ($q) use ($request) {
                    return $q->where('user_job_data.job_title', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('user_job_data.personal_number', 'LIKE', '%' . $request->search . '%');
                });
        }
        if ($request->location) {
            $model->whereHas('jobData', function ($q) use ($request) {
                return $q->where('user_location_id', $request->location);
            });
        }
        if ($request->team) {
            $model->whereHas('teams', function ($q) use ($request) {
                return $q->where('user_teams.id', $request->team);
            });
        }
        if ($request->department) {
            $model->whereHas('teams', function ($q) use ($request) {
                return $q->whereHas('department', function ($q) use ($request) {
                    return $q->where('user_departments.id', $request->department);
                });
            })->orWhereHas('jobData', function ($q) use ($request) {
                return $q->whereHas('userJobDepartments', function ($q) use ($request) {
                    return $q->where('user_job_departments.department_id', $request->department);
                });
            });
        }

        $models = $model->paginate($per_page);

        $get_permissions = $request->get('auth_user_permissions');
        if (!in_array('user-profile.list', $get_permissions)) {
            $model_collect = $models->map(function ($item) {
                return [
                    'id' => $item->id,
                    'employee' => $item->first_name . ' ' . $item->last_name,
                    'firstName' => $item->first_name,
                    'lastName' => $item->last_name,
                    'userId' => $item->user_id,
                ];
            });
            return response()->json([
                'data' => $model_collect,
            ]);
        }

        $model_collect = $models->map(function ($item) {
            $teams_model = $item->teams()->get();
            $department_names = $teams_model->map(function ($item) {
                return !empty($item->department) ? $item->department->name : false;
            });
            $department = array_unique($department_names->toArray());
            $user_job_departments = $item?->jobData?->userJobDepartments ?? [];
            $department = array_unique([...$department, ...(collect($user_job_departments)->map(function ($item) {
                return $item->name;
            }))->toArray()]);
            $team_names = $teams_model->map(function ($item) {
                return $item->name;
            });
            $teams = array_unique($team_names->toArray());
            $profile_pic_url = $this->getProfilePicture($item->user_id ?? null) ?? null;
            return [
                'id' => $item->id,
                'employee' => $item->first_name . ' ' . $item->last_name,
                'firstName' => $item->first_name,
                'lastName' => $item->last_name,
                'email' => $item->email,
                'userId' => $item->user_id,
                'dateOfBirth' => $item->date_of_birth,
                'teams' => !empty($teams_model) ? implode(', ', $teams) : [],
                'departments' => !empty($department) ? implode(', ', $department) : '',
                'personalNumber' => $item->jobData->personal_number ?? '',
                'jobTitle' => $item->jobData->job_title ?? '',
                'skills' => $this->memberSkills($item?->jobData?->job?->jobSkillGroups),
                'joinDate' => $item->jobData->join_date ?? '',
                'location' => $item->jobData->location?->name ?? '',
                'exitDate' => $item->jobData->exit_date ?? '',
                'grossSalary' => $item->userCompensationData->gross_monthly_salary ?? '',
                'country' => $item->country ?? '',
                'profilePicUrl' => $profile_pic_url,
            ];
        });
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model_collect = $this->applySorting($model_collect, $sort_by, $sort_order);
        }
        return response()->json([
            'data' => $model_collect,
            'links' => $models->links(),
            'total' => $models->total(),
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

    private function memberSkills($groups)
    {
        $skills = [];
        if (!empty($groups)) {
            foreach ($groups as $group) {
                $skills[] = $group->groupSkills->map(function ($skill) {
                    return [
                        'skillId' => $skill?->id,
                        'skillName' => $skill?->name,
                        'skillLevel' => $skill?->level,
                    ];
                });
            }
        }
        return $skills;
    }

    /**
     * Save user profile data information
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveProfileData(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'birthName' => 'nullable|string|max:255',
            'dateOfBirth' => 'required|date',
            'countryOfBirth' => 'required|string|max:255',
            'placeOfBirth' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'highestSchoolDegree' => 'nullable|integer',
            'highestEducationLevel' => 'nullable|integer',
            'address' => 'nullable|string|max:255',
            'street' => 'required|string',
            'secondaryAddress' => 'nullable|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'publicPhone' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'email' => 'required',
            'childrenData' => 'nullable|array',
            'financeDepartmentNumber' => 'nullable|integer',
            'financeCategory' => 'nullable|integer',
            'religion' => 'nullable|string|max:255',
            'religionOfPartner' => 'nullable|string|max:255',
            'freeAmountChildren' => 'nullable|integer',
            'freeAmountYearly' => 'nullable|integer',
            'freeAmountMonthly' => 'nullable|integer',
            'taxLiability' => 'nullable|string',
            'accountOwner' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'bic' => 'nullable|string|max:255',
            'bankName' => 'nullable|string|max:255',
            'socialSecurityNumber' => 'nullable|string',
            'healthInsurance' => 'nullable|string|max:255',
            'previousHealthInsurance' => 'nullable|string|max:255',
            'groupPeople' => 'nullable|integer',
            'contributionGroup' => 'nullable|integer',
            'accidentInsurance' => 'nullable|string|max:255',
            'tariffPoints' => 'nullable|string',
            'percentageAssociation' => 'nullable|string',
            'userId' => 'required',
        ]);
        $action = 'updateEmployee';
        if (isset($request->id)) {
            $model = UserProfileData::where('id', $request->id)->first();
        } else {
            $model = new UserProfileData();
            $action = 'createEmployee';
        }
        $model->first_name = $request->firstName;
        $model->last_name = $request->lastName;
        $model->birth_name = $request->birthName;
        $model->public_phone = $request->publicPhone;
        $model->date_of_birth = $request->dateOfBirth;
        $model->country_of_birth = $request->countryOfBirth;
        $model->place_of_birth = $request->placeOfBirth;
        $model->gender = $request->gender;
        $model->nationality = $request->nationality;
        $model->highest_school_degree_id = $request->highestSchoolDegree;
        $model->highest_education_level_id = $request->highestEducationLevel;
        $model->marital_status = $request->maritalStatus ?? "single";

        $model->street = $request->street;
        $model->address = $request->address;
        $model->secondary_address = $request->secondaryAddress;
        $model->zip_code = $request->zipCode;
        $model->city = $request->city;
        $model->state = $request->state;
        $model->country = $request->country;

        $model->phone = $request->phone;
        $model->mobile = $request->mobile;
        $model->email = $request->email;

        $model->children_data = $request->childrenData;

        $model->finance_department_number = $request->financeDepartmentNumber;
        $model->finance_category = $request->financeCategory;
        $model->religion = $request->religion;
        $model->religion_of_partner = $request->religionOfPartner;
        $model->free_amount_children = $request->freeAmountChildren;
        $model->free_amount_yearly = $request->freeAmountYearly;
        $model->free_amount_monthly = $request->freeAmountMonthly;
        $model->tax_liability = $request->taxLiability;

        $model->account_owner = $request->accountOwner;
        $model->iban = $request->iban;
        $model->bic = $request->bic;
        $model->bank_name = $request->bankName;
        $model->social_security_number = $request->socialSecurityNumber;
        $model->health_insurance = $request->healthInsurance;
        $model->previous_health_insurance = $request->previousHealthInsurance;
        $model->group_people = $request->groupPeople;
        $model->contribution_group = $request->contributionGroup;
        $model->accident_insurance = $request->accidentInsurance;
        $model->tariff_points = $request->tariffPoints;
        $model->percentage_association = $request->percentageAssociation;
        $model->private_email = $request->privateEmail;

        $model->user_id = $request->userId;

        $model->save();
        $this->prepareEloContent($model->id, $action, 'update in profile data');
        return response()->json(['success' => true, 'message' => 'Profile updated.', 'userId' => $model->id], 200);
    }

    /**
     * Display user profile data
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showProfileData(Request $request, $id)
    {
        $model = new UserProfileData();
        if ($request->getByUserId == true) {
            $model = $model->where('user_id', $id)->first();
        } else {
            $model = $model->where('id', $id)->first();
        }
        $get_permissions = $request->get('auth_user_permissions');
        if (!in_array('user-profile.list', $get_permissions)) {
            if ($model->user_id != $request->get('auth_user_id')) {
                throw new \Exception('Not enough permissions to access this module');
            }
        }
        return response()->json([
            'data' => [
                'id' => $model->id,
                'firstName' => $model->first_name,
                'lastName' => $model->last_name,
                'personalNumber' => $model->jobData->personal_number ?? '',
                'birthName' => $model->birth_name,
                'publicPhone' => $model->public_phone,
                'dateOfBirth' => $model->date_of_birth,
                'countryOfBirth' => $model->country_of_birth,
                'placeOfBirth' => $model->place_of_birth,
                'gender' => $model->gender,
                'nationality' => $model->nationality,
                'highestSchoolDegree' => $model->highestSchoolDegree ? [
                    'id' => $model->highestSchoolDegree?->id,
                    'name' => $model->highestSchoolDegree?->name,
                ] : null,
                'highestEducationLevel' => $model->highestEducationLevel ? [
                    'id' => $model->highestEducationLevel?->id,
                    'name' => $model->highestEducationLevel?->name,
                ] : null,
                'maritalStatus' => $model->marital_status ?? "single",

                'street' => $model->street,
                'address' => $model->address,
                'secondaryAddress' => $model->secondary_address,
                'zipCode' => $model->zip_code,
                'city' => $model->city,
                'state' => $model->state,
                'country' => $model->country,

                'phone' => $model->phone,
                'mobile' => $model->mobile,
                'email' => $model->email,

                'childrenData' => $model->children_data ?? [],

                'financeDepartmentNumber' => $model->finance_department_number,
                'financeCategory' => $model->finance_category,
                'religion' => $model->religion,
                'religionOfPartner' => $model->religion_of_partner,
                'freeAmountChildren' => $model->free_amount_children,
                'freeAmountYearly' => $model->free_amount_yearly,
                'freeAmountMonthly' => $model->free_amount_monthly,
                'taxLiability' => $model->tax_liability,

                'accountOwner' => $model->account_owner,
                'iban' => $model->iban,
                'bic' => $model->bic,
                'bankName' => $model->bank_name,

                'socialSecurityNumber' => $model->social_security_number,
                'healthInsurance' => $model->health_insurance,
                'previousHealthInsurance' => $model->previous_health_insurance,
                'groupPeople' => $model->group_people,
                'contributionGroup' => $model->contribution_group,

                'accidentInsurance' => $model->accident_insurance,
                'tariffPoints' => $model->tariff_points,
                'percentageAssociation' => $model->percentage_association,
                'privateEmail' => $model->private_email,
                'supervisorId' => $model->jobData?->supervisor_id,
                'department' => $model->department?->name,
                'location' => $model->jobData?->location?->name,
                'userId' => $model->user_id,
            ],
        ]);
    }

    /**
     * Save user job data information
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveJobData(Request $request)
    {
        $request->validate([
            'jobTitle' => 'required|string',
            'jobDescription' => 'nullable|string',
            'personalNumber' => 'required|string',
            'contractType' => 'required|string',
            'supervisorId' => 'nullable',
            'joinDate' => 'required|date',
            'exitDate' => 'nullable|date',
            'isMainJob' => 'required|boolean',
            'accountingDate' => 'required|date',
            'probationPeriodMonths' => 'nullable|integer',
            'probationEndDate' => 'nullable|date',
            'costCenter' => 'nullable|numeric',
            'isEmployeeLeasing' => 'required|boolean',
            'locationId' => 'required',
            'totalAnnualLeaves' => 'required|numeric',
            'userId' => 'required',
            'workHoursArray' => 'required|array|min:1',
            'workHoursArray.*.day' => 'distinct',
        ]);
        $model = UserJobData::firstOrNew(['user_profile_id' => $request->userId]);
        $model->job_title = $request->jobTitle;
        $model->job_description = $request->jobDescription;
        $model->personal_number = $request->personalNumber;
        $model->job_id = $request->jobNumber;

        $model->probation_period_months = $request->probationPeriodMonths ?? null;
        $model->probation_end_date = $request->probationEndDate ?? null;
        $model->contract_type = $request->contractType;
        $model->supervisor_id = $request->supervisorId;
        $model->join_date = $request->joinDate;
        $model->exit_date = $request->exitDate;
        $model->accounting_date = $request->accountingDate;
        $model->is_main_job = $request->isMainJob;
        $model->cost_center = $request->costCenter;
        $model->is_employee_leasing = $request->isEmployeeLeasing;
        $model->user_location_id = $request->locationId['id'] ?? null;
        $model->working_days = $request->workingDays;
        $model->weekly_hours = !empty($request->workHoursArray) ? collect($request->workHoursArray)->sum('numOfHours') : 0;
        $model->total_annual_leaves = $request->totalAnnualLeaves;
        $model->remaining_leaves = $request->remainingLeaves;
        $model->remaining_leaves_year = $request->remainingLeavesYear;
        $model->user_profile_id = $request->userId;
        $model->target_value_year = $request->targetValueYear;
        $model->target_value_month = $request->targetValueMonth;
        $model->user_profile_id = $request->userId;
        if ($model->isDirty('accounting_date')) {
            $this->deleteTimeTrackerRecordsBeforeAccountingDate(Carbon::parse($model->accounting_date), $request->userId);
        }
        $model->save();
        UserJobLeave::updateOrCreate(
            [
                'leave_year' => Carbon::now()->format('Y'),
                'user_job_id' => $model->id,
            ],
            [
                'user_job_id' => $model->id,
                'total_annual_leaves' => $request->totalAnnualLeaves,
                'additional_leaves' => $request->additionalLeaves,
                'leave_year' => Carbon::now()->format('Y'),
            ]
        );
        if (!empty($request->workHoursArray)) {
            // Create an array of unique day values from the workHoursArray
            $days = collect($request->workHoursArray)->pluck('day')->unique();

            // Update or create UserWorkingHour records
            foreach ($request->workHoursArray as $data) {
                if (isset($data['day']) && isset($data['numOfHours'])) {
                    UserWorkingHour::updateOrCreate(
                        ['user_job_id' => $model->id, 'day' => $data['day']],
                        ['working_hours' => $data['numOfHours']]
                    );
                }
            }

            // Delete UserWorkingHour records where day is not in the workHoursArray
            UserWorkingHour::where('user_job_id', $model->id)
                ->whereNotIn('day', $days)
                ->delete();
        }
        //departments
        $departments = collect($request->departments);
        $departmentIds = $departments->pluck('id')->toArray();
        $model->userJobDepartments()->sync($departmentIds);

        //Sync teams with select user
        $user_profile = UserProfileData::find($request->userId);
        $user_profile->teams()->sync($request->teams ?? []);
        $this->prepareEloContent($request->userId, 'updateEmployee', 'update in Job data');
        return response()->json(['success' => true, 'message' => 'Job Data updated.'], 200);
    }

    /**
     * Deletes all the records for a person before accounting date
     * @param  int  $id
     * @return void
     */
    private function deleteTimeTrackerRecordsBeforeAccountingDate($date, $user_id): void
    {
        /* commenting till fully tested on all ends
    $time_tracker_government_records = TimeTrackerGovernment::whereDate('date', '<', $date)->where("type", "!=", "break")->where('user_id', $user_id)->get();

    $time_tracker_government_records->map(function ($record) use ($user_id) {
    $month_first_day = Carbon::parse($record->date)->startOfMonth()->toDateString();   //Get first day of the selected date month
    $month_last_day = Carbon::parse($record->date)->endOfMonth()->toDateString();
    $time_tracker_user_data = TimeTrackerUserData::where([
    ['monthly_start_date', '=', $month_first_day],
    ['month_last_day', '=', $month_last_day],
    ['user_id', '=', $user_id],
    ])->first();
    if (!empty($time_tracker_user_data)) {
    $start_datetime = Carbon::createFromFormat('H:i:s', $record->start_time);
    $end_datetime = Carbon::createFromFormat('H:i:s', $record->end_time);

    // Calculate the difference in hours
    $hours_difference = $start_datetime->diffInHours($end_datetime);
    $time_tracker_user_data->actual_worked_hours = $time_tracker_user_data->actual_worked_hours - $hours_difference;
    $time_tracker_user_data->save();
    }
    }); */
    }

    /**
     * Display user Job data
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showJobDataByUser(Request $request, $id)
    {
        return $this->userProfileData($id);
    }

    /**
     * Display user Job data
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showBasicJobDataByUser($id)
    {
        $user_profile = UserProfileData::where('user_id', $id)->first();
        if (!$user_profile) {
            return response()->json([
                'success' => false,
                'message' => 'No user profile exists for this user',
            ], 404);
        }
        $model = $user_profile->jobData;
        $team_lead_ids = $user_profile->teams->map(function ($team) {
            return isset($team->team_lead_id) ? $team->team_lead_id : null;
        });
        $supervisor_count = UserJobData::where('supervisor_id', $user_profile->user_id)->count();
        $is_approver = in_array($user_profile->user_id, $team_lead_ids->toArray());
        // if the user is a department head then he is a approver as well
        $departments = UserDepartment::where('department_head_id', $user_profile->user_id)->count();
        if ($supervisor_count > 0) {
            $is_approver = true;
        }
        if ($departments > 0) {
            $is_approver = true;
        }
        if ($model) {
            return response()->json([
                'data' => [
                    'id' => $model->id,
                    'jobTitle' => $model->job_title,
                    'jobDescription' => $model->job_description,
                    'personalNumber' => $model->personal_number,
                    'country' => $user_profile->country ?? '',
                    'jobNumber' => [
                        'id' => $model?->job?->id,
                        'jobNumber' => $model?->job?->j_number,
                        'jobTitle' => $model?->job?->j_title,
                        'jobLevel' => $model?->job?->jobLevel->level_name,
                        'contractType' => $model?->job?->contracType->name,
                        'createdAt' => \Illuminate\Support\Carbon::parse($model?->job?->created_at)->format('d/m/y'),
                    ],
                    'teams' => $user_profile->teams->map(function ($item_profile) {
                        $url = $this->getProfilePicture($item_profile->team_lead_id ?? null) ?? null;
                        return [
                            'id' => $item_profile->id,
                            'name' => $item_profile->name,
                            'teamLead' => $item_profile->teamLead,
                            'department' => $item_profile->department,
                            'profilePicUrl' => $url,
                        ];
                    }),
                    'isApprover' => $is_approver,
                    'getImmediateSupervisor' => $user_profile->getImmediateSupervisor()
                ],
            ]);
        } else {
            return response()->json([
                'data' => [
                    'jobTitle' => '',
                    'jobDescription' => '',
                    'personalNumber' => '',
                    'jobNumber' => '',
                    'teams' => '',
                    'contractType' => '',
                    'supervisorId' => '',
                    'joinDate' => '',
                    'exitDate' => '',
                    'isMainJob' => '',
                    'costCenter' => '',
                    'isEmployeeLeasing' => '',
                    'defaultWorkspace' => '',
                    'workingDays' => '',
                    'weeklyHours' => '',
                    'totalAnnualLeaves' => '',
                    'remainingLeaves' => '',
                    'remainingLeavesYear' => '',
                    'userId' => '',
                ],
            ]);
        }
    }

    /**
     * Display user Job data
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showJobData($id)
    {
        $model = UserJobData::with('userJobLeave', 'userJobDepartments')->where('user_profile_id', $id)->first();
        if ($model) {
            $teams_model = $model->user->teams()->get();
            $userJobDepartments = $model->userJobDepartments->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'department_head_id' => $item->department_head_id,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'is_top_level' => $item->is_top_level,
                ];
            });
            // Access the associated userJobDepartments
            //$userJobDepartments = $model->userJobDepartments[0]->department;
            //            $userJobDepartments = $model->userJobDepartments->map(function ($item) {
            //                return $item->department;
            //            });
            //            $department_names = $teams_model->map(function ($item) {
            //                return !empty($item->department) ? $item->department->name : false;
            //            });
            $getLeaveData = LeaveHelper::getLeaveInfo($id);
            //$department = array_unique($department_names->toArray());
            return response()->json([
                'data' => [
                    'id' => $model->id,
                    'jobTitle' => $model->job_title,
                    'jobDescription' => $model->job_description,
                    'personalNumber' => $model->personal_number,
                    'jobNumber' => [
                        'id' => $model?->job?->id,
                        'jobNumber' => $model?->job?->j_number,
                        'jobTitle' => $model?->job?->j_title,
                        'jobLevel' => $model?->job?->jobLevel->level_name,
                        'contractType' => $model?->job?->contracType->name,
                        'createdAt' => \Illuminate\Support\Carbon::parse($model?->job?->created_at)->format('d/m/y'),
                    ],
                    'teams' => !empty($teams_model) ? $teams_model->map(function ($item_profile) {
                        return [
                            'id' => $item_profile->id,
                            'name' => $item_profile->name,
                            'teamLead' => $item_profile->teamLead,
                            'department' => $item_profile->department,
                        ];
                    }) : [],
                    //'departments' => !empty($department) ? implode(", ", $department) : '',
                    'departments' => $userJobDepartments,
                    'contractType' => $model->contract_type,
                    'supervisorId' => $model->supervisor_id,
                    'joinDate' => $model->join_date,
                    'exitDate' => $model->exit_date,
                    'isMainJob' => $model->is_main_job,
                    'probationPeriodMonths' => $model->probation_period_months ?? '',
                    'probationEndDate' => $model->probation_end_date ?? '',
                    'accountingDate' => $model->accounting_date,
                    'costCenter' => $model->cost_center,
                    'isEmployeeLeasing' => $model->is_employee_leasing,
                    'locationId' => !empty($model->location) ? $model->location : '',
                    'workingDays' => $model->working_days,
                    'weeklyHours' => $model->weekly_hours,
                    'totalAnnualLeaves' => $getLeaveData['annualLeaves'],
                    'remainingLeaves' => $getLeaveData['totalRemainingLeaves'],
                    'additionalLeaves' => $getLeaveData['additionalLeaves'],
                    'remainingLeavesYear' => $model->remaining_leaves_year,
                    'previousYearRemainingLeaves' => $getLeaveData['previousYearRemainingLeaves'],
                    'userId' => $model->user_profile_id,
                    'targetValueYear' => $model->target_value_year,
                    'targetValueMonth' => $model->target_value_month,
                    'workHours' => $model->workingHours->map(function ($item) {
                        return [
                            'days' => [$item->day],
                            'numOfHours' => $item->working_hours,
                        ];
                    }),
                ],
            ]);
        } else {
            return response()->json([
                'data' => [
                    'jobTitle' => '',
                    'jobDescription' => '',
                    'personalNumber' => '',
                    'jobNumber' => '',
                    'teams' => [],
                    'departments' => [],
                    'contractType' => '',
                    'supervisorId' => '',
                    'joinDate' => '',
                    'exitDate' => '',
                    'isMainJob' => '',
                    'probationPeriodMonths' => '',
                    'probationEndDate' => '',
                    'additionalLeaves' => 0,
                    'costCenter' => '',
                    'isEmployeeLeasing' => '',
                    'locationId' => '',
                    'workingDays' => '',
                    'weeklyHours' => '',
                    'totalAnnualLeaves' => '',
                    'remainingLeaves' => '',
                    'remainingLeavesYear' => '',
                    'userId' => '',
                    'workHours' => [],
                    'targetValueYear' => 0,
                    'targetValueMonth' => 0,
                ],
            ]);
        }
    }

    /**
     * Save user compensation data information
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveCompensationData(Request $request)
    {
        $request->validate([
            'userId' => 'required|numeric',
        ]);

        $model = UserCompensationData::firstOrNew(['user_profile_id' => $request->userId]);
        $model->compensation_type = $request->compensationType;
        $model->gross_monthly_salary = $request->grossMonthlySalary;
        $model->gross_hourly_salary = $request->grossHourlySalary;
        $model->contract_number = $request->contractNumber;
        $model->insurance_company = $request->insuranceCompany;
        $model->user_profile_id = $request->userId;
        $model->amount_monthly = $request->amountMonthly;
        $model->save();
        if (!empty($request->bonus)) {
            $bonuses = collect($request->bonus);
            $bonuses->map(function ($bonus) use ($model) {
                if (isset($bonus['targetType'])) {
                    $compensation_bonus = CompensationBonus::firstOrNew(['compensation_id' => $model->id, 'level' => $bonus['level']]);
                    $compensation_bonus->target_type = $bonus['targetType'];
                    if ($bonus['targetType'] == 'consulting_individual_value') {
                        $compensation_bonus->month = $bonus['month'] ?? 0;
                        $compensation_bonus->half_year = $bonus['halfYear'] ?? 0;
                        $compensation_bonus->year = $bonus['year'] ?? 0;
                        $compensation_bonus->bonus_faktor = $bonus['bonusFaktor'] ?? 0;
                    } elseif ($bonus['targetType'] == 'service_individual_value' || $bonus['targetType'] == 'service_team_value') {
                        $compensation_bonus->percent_number = $bonus['percentNumber'] ?? '';
                        $compensation_bonus->goal = $bonus['goal'] ?? null;
                        $compensation_bonus->bonus_faktor = $bonus['bonusFaktor'] ?? 0;
                    } elseif (isset($bonus['targetValue'])) {
                        $compensation_bonus->target_value = $bonus['targetValue'];
                    }
                    $compensation_bonus->save();
                }
            });
        }
        $this->prepareEloContent($request->userId, 'updateEmployee', 'Update in Compensation data');
        return response()->json(['success' => true, 'message' => 'Compensation Data updated.'], 200);
    }

    /**
     * Display user compensation data
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCompensationData($id)
    {
        $model = UserCompensationData::where('user_profile_id', $id)->first();

        if ($model) {
            return response()->json([
                'data' => [
                    'id' => $model->id,
                    'compensationType' => $model->compensation_type,
                    'grossMonthlySalary' => $model->gross_monthly_salary,
                    'grossHourlySalary' => $model->gross_hourly_salary,
                    'contractNumber' => $model->contract_number,
                    'insuranceCompany' => $model->insurance_company,
                    'amountMonthly' => $model->amount_monthly,
                    'bonus' => $model->bonuses->isEmpty() ? [
                        [
                            'targetType' => '',
                            'targetValue' => '',
                            'level' => 'one',
                            'month' => '',
                            'halfYear' => '',
                            'year' => '',
                            'bonusFaktor' => '',
                            'goal' => '',
                            'percentNumber' => '',
                        ], [
                            'targetType' => '',
                            'targetValue' => '',
                            'level' => 'two',
                            'month' => '',
                            'halfYear' => '',
                            'year' => '',
                            'bonusFaktor' => '',
                            'goal' => '',
                            'percentNumber' => '',
                        ], [
                            'targetType' => '',
                            'targetValue' => '',
                            'level' => 'three',
                            'month' => '',
                            'halfYear' => '',
                            'year' => '',
                            'bonusFaktor' => '',
                            'goal' => '',
                            'percentNumber' => '',
                        ],
                        [
                            'targetType' => '',
                            'targetValue' => '',
                            'level' => 'four',
                            'month' => '',
                            'halfYear' => '',
                            'year' => '',
                            'bonusFaktor' => '',
                            'goal' => '',
                            'percentNumber' => '',
                        ],
                        [
                            'targetType' => '',
                            'targetValue' => '',
                            'level' => 'five',
                            'month' => '',
                            'halfYear' => '',
                            'year' => '',
                            'bonusFaktor' => '',
                            'goal' => '',
                            'percentNumber' => '',
                        ],
                    ] : collect(['one', 'two', 'three', 'four', 'five'])->map(function ($level) use ($model) {
                        $bonus = $model->bonuses->firstWhere('level', $level);
                        return [
                            'targetType' => $bonus ? $bonus->target_type : '',
                            'targetValue' => $bonus ? $bonus->target_value : '',
                            'month' => $bonus ? $bonus->month : '',
                            'halfYear' => $bonus ? $bonus->half_year : '',
                            'year' => $bonus ? $bonus->year : '',
                            'bonusFaktor' => $bonus ? $bonus->bonus_faktor : '',
                            'level' => $level,
                            'goal' => $bonus ? $bonus->goal : '',
                            'percentNumber' => $bonus ? $bonus->percent_number : '',
                        ];
                    }),
                ],
            ]);
        } else {
            return response()->json([
                'data' => [
                    'compensationType' => '',
                    'grossMonthlySalary' => '',
                    'grossHourlySalary' => '',
                    'contractNumber' => '',
                    'insuranceCompany' => '',
                    'amountMonthly' => '',
                    'bonus' => [
                        [
                            'targetType' => '',
                            'targetValue' => '',
                            'level' => 'one',
                            'month' => '',
                            'halfYear' => '',
                            'year' => '',
                            'bonusFaktor' => '',
                            'goal' => '',
                            'percentNumber' => '',
                        ], [
                            'targetType' => '',
                            'targetValue' => '',
                            'level' => 'two',
                            'month' => '',
                            'halfYear' => '',
                            'year' => '',
                            'bonusFaktor' => '',
                            'goal' => '',
                            'percentNumber' => '',
                        ], [
                            'targetType' => '',
                            'targetValue' => '',
                            'level' => 'three',
                            'month' => '',
                            'halfYear' => '',
                            'year' => '',
                            'bonusFaktor' => '',
                            'goal' => '',
                            'percentNumber' => '',
                        ],
                        [
                            'targetType' => '',
                            'targetValue' => '',
                            'level' => 'four',
                            'month' => '',
                            'halfYear' => '',
                            'year' => '',
                            'bonusFaktor' => '',
                            'goal' => '',
                            'percentNumber' => '',
                        ],
                        [
                            'targetType' => '',
                            'targetValue' => '',
                            'level' => 'five',
                            'month' => '',
                            'halfYear' => '',
                            'year' => '',
                            'bonusFaktor' => '',
                            'goal' => '',
                            'percentNumber' => '',
                        ],
                    ],
                ],
            ]);
        }
    }

    //upload a document and contract
    public function uploadDocumentAndContract(Request $request)
    {
        $request->validate([
            'file' => 'required',
            'userProfileId' => 'required',
        ]);
        $document_contract = new DocumentAndContract();
        $file = $request->file;
        $original_name = $file->getClientOriginalName();
        $file_name_to_store = $request->userProfileId . '_' . $original_name;
        Storage::disk('local')->putFileAs('user_profile/files/', $file, $file_name_to_store);
        $document_contract->stored_file_name = $file_name_to_store;
        $document_contract->file_name = $original_name;
        $document_contract->user_profile_id = $request->userProfileId;
        $document_contract->size = $file->getSize() ?? null;
        $document_contract->save();
        $this->prepareEloContent($request->userProfileId, 'createEmployeeDocument', 'added a new document and contract');
        return response()->json(['message' => 'File added'], 200);
    }

    //delete a document and contract
    public function deleteDocumentAndContract(Request $request)
    {
        $request->validate([
            'storedFileName' => 'required',
        ]);
        //delete the file from storage
        Storage::disk('local')->delete('user_profile/files/' . $request->storedFileName);
        $document_contract = DocumentAndContract::where('stored_file_name', $request->storedFileName)->firstOrFail();
        $this->prepareEloContent($document_contract?->user_profile_id, 'deleteEmployeeDocument', 'Deletion in Document and contract');
        $document_contract->delete();
        return response()->json(['message' => 'File deleted'], 200);
    }

    /**
     * Get document and contract of user
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listDocumentAndContract(Request $request)
    {
        $request->validate([
            'userProfileId' => 'required',
        ]);
        $document_and_contracts = DocumentAndContract::where('user_profile_id', $request->userProfileId)->get();
        $document_and_contract_collection = $document_and_contracts->map(function ($document_and_contract) {
            return [
                'fileName' => $document_and_contract->file_name,
                'storedFileName' => $document_and_contract->stored_file_name,
                'size' => $document_and_contract->size,
                'userProfileId' => $document_and_contract->user_profile_id,
            ];
        });
        return response()->json(['documentAndContact' => $document_and_contract_collection]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_profile = UserProfileData::findOrFail($id);
        $this->prepareEloContent($user_profile->id, 'deleteEmployee', 'Deleted profile data');
        $user_profile->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    /**
     * Get the approval permissions list for the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApprovalPermissions(Request $request)
    {
        // Initialize data
        $request_approver_data = [];

        // Get the ID of the current authenticated user
        $user_id = $this->getAuthUserId($request);

        // Check if the current user is defined as a team lead
        $is_team_lead = false;
        $user_team = UserTeam::where('team_lead_id', $user_id)->select('id')->first();
        if ($user_team) {
            $is_team_lead = true;
            $request_approver_data['team_lead_id'] = $user_team->id;
        }

        // Check if the current user is defined as a department head
        $is_department_head = false;
        $user_department = UserDepartment::where('department_head_id', $user_id)->select('id')->first();
        if ($user_department) {
            $is_department_head = true;
            $request_approver_data['department_head_id'] = $user_department->id;
        }

        // Check if the current user is a project manager for a given project
        $is_project_manager = false;
        $project = Project::where('user_id', $user_id)->get();
        if (isset($project['id'])) {
            $is_project_manager = true;
            $request_approver_data['project_ids'] = $project->pluck('id');
        }

        // Check if the current user is a supervisor
        $is_supervisor = UserJobData::where('supervisor_id', $user_id)->exists();

        // Determine if the current user is a vacation request approver
        $is_vacation_request_approver = $is_team_lead || $is_supervisor || $is_department_head;

        // Determine if the current user is a travel expense approver
        $is_travel_expense_approver = $is_team_lead || $is_project_manager || $is_department_head;

        // Return the approval list as a JSON response
        return response()->json([
            'isVacationRequestApprover' => $is_vacation_request_approver,
            'isTravelExpenseApprover' => $is_travel_expense_approver,
            'requestApproverData' => $request_approver_data,
        ], 200);
    }

    public function profile(Request $request, $id)
    {
        $authUserId = $request->request->get('auth_user_id');
        if ($authUserId !== $id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized user id',
            ], 403);
        }

        return $this->userProfileData($id);
    }

    private function prepareEloContent($userId, $action = 'update', $message = null): void
    {
        $model = UserProfileData::with('jobData', 'jobData.workingHours', 'jobData.location', 'jobData.userJobLeave', 'userCompensationData', 'teams', 'documentAndContract')->where('id', $userId)->first();
        $fleet = FleetDriver::with('fleetCar', 'previouslyOwnedCars')->where('user_id', $model?->user_id)->first();
        $model->asset = $fleet ? $fleet->toArray() : null;
        $content = [
            'moduleAction' => $action,
            'message' => $message,
            'data' => [
                'profile' => $model->toArray(),
            ],
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $model);
    }

    /**
     * gets basic employee info
     * @param integer $id
     */
    public function getBasicEmployeeInfo($id)
    {
        $user_profile = UserProfileData::where('user_id', $id)->first();
        if ($user_profile) {
            $teams_model = $user_profile->teams()->get();
            $user_job_departments = $user_profile?->jobData?->userJobDepartments->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            }) ?? [];

            return response()->json([
                'id' => $user_profile->id,
                'personalNumber' => $user_profile?->jobData?->personal_number,
                'firstName' => $user_profile->first_name,
                'lastName' => $user_profile->last_name,
                'location' => $user_profile?->jobData?->location?->name,
                'teams' => !empty($teams_model) ? $teams_model->map(function ($item_profile) {
                    return [
                        'id' => $item_profile->id,
                        'name' => $item_profile->name,
                    ];
                }) : [],
                'departments' => $user_job_departments,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No user profile exists for this user',
            ], 404);
        }
    }

    private function userProfileData($id)
    {
        $user_profile = UserProfileData::where('user_id', $id)->first();
        if (!$user_profile) {
            return response()->json([
                'success' => false,
                'message' => 'No user profile exists for this user',
            ], 404);
        }
        $model = $user_profile->jobData;
        $team_lead_ids = $user_profile->teams->map(function ($team) {
            return isset($team->team_lead_id) ? $team->team_lead_id : null;
        });
        $supervisor_count = UserJobData::where('supervisor_id', $user_profile->user_id)->count();
        $is_approver = in_array($user_profile->user_id, $team_lead_ids->toArray());
        // if the user is a department head then he is a approver as well
        $departments = UserDepartment::where('department_head_id', $user_profile->user_id)->count();
        if ($supervisor_count > 0) {
            $is_approver = true;
        }
        if ($departments > 0) {
            $is_approver = true;
        }
        if ($model) {
            return response()->json([
                'data' => [
                    'id' => $model->id,
                    'jobTitle' => $model->job_title,
                    'jobDescription' => $model->job_description,
                    'personalNumber' => $model->personal_number,
                    'jobNumber' => [
                        'id' => $model?->job?->id,
                        'jobNumber' => $model?->job?->j_number,
                        'jobTitle' => $model?->job?->j_title,
                        'jobLevel' => $model?->job?->jobLevel->level_name,
                        'contractType' => $model?->job?->contracType->name,
                        'createdAt' => \Illuminate\Support\Carbon::parse($model?->job?->created_at)->format('d/m/y'),
                    ],
                    'teams' => $user_profile->teams->map(function ($item_profile) {
                        $url = $this->getProfilePicture($item_profile->team_lead_id ?? null) ?? null;
                        return [
                            'id' => $item_profile->id,
                            'name' => $item_profile->name,
                            'teamLead' => $item_profile->teamLead,
                            'department' => $item_profile->department,
                            'profilePicUrl' => $url,
                        ];
                    }),
                    'contractType' => $model->contract_type,
                    'supervisorId' => $model->supervisor_id,
                    'joinDate' => $model->join_date,
                    'exitDate' => $model->exit_date,
                    'isMainJob' => $model->is_main_job,
                    'costCenter' => $model->cost_center,
                    'isEmployeeLeasing' => $model->is_employee_leasing,
                    'defaultWorkspace' => $model->default_workspace,
                    'totalAnnualLeaves' => $model->total_annual_leaves,
                    'remainingLeaves' => $model->remaining_leaves,
                    'remainingLeavesYear' => $model->remaining_leaves_year,
                    'userId' => $model->user_profile_id,
                    'targetValueYear' => $model->target_value_year,
                    'targetValueMonth' => $model->target_value_month,
                    'workHours' => $model->workingHours->map(function ($item) {
                        return [
                            'days' => [$item->day],
                            'numOfHours' => $item->working_hours,
                        ];
                    }),
                    'isApprover' => $is_approver,
                    'location' => $user_profile->jobData->location,
                ],
            ]);
        } else {
            return response()->json([
                'data' => [
                    'jobTitle' => '',
                    'jobDescription' => '',
                    'personalNumber' => '',
                    'jobNumber' => '',
                    'teams' => '',
                    'contractType' => '',
                    'supervisorId' => '',
                    'joinDate' => '',
                    'exitDate' => '',
                    'isMainJob' => '',
                    'costCenter' => '',
                    'isEmployeeLeasing' => '',
                    'defaultWorkspace' => '',
                    'probationPeriodMonths' => '',
                    'probationEndDate' => '',
                    'workingDays' => '',
                    'weeklyHours' => '',
                    'totalAnnualLeaves' => '',
                    'remainingLeaves' => '',
                    'remainingLeavesYear' => '',
                    'additionalLeaves' => 0,
                    'userId' => '',
                    'workHours' => [],
                    'location' => [],
                ],
            ]);
        }
    }

    public function getAllEmployeeInfo($id)
    {
        $model = UserProfileData::where('user_id', $id)->first();
        if ($model) {
            return response()->json([
                'id' => $model->id,
                'firstName' => $model->first_name,
                'lastName' => $model->last_name,
                'employee' => $model->full_name,
                'birthName' => $model->birth_name,
                'publicPhone' => $model->public_phone,
                'dateOfBirth' => $model->date_of_birth,
                'countryOfBirth' => $model->country_of_birth,
                'placeOfBirth' => $model->place_of_birth,
                'gender' => $model->gender,
                'nationality' => $model->nationality,
                'highestSchoolDegree' => $model->highestSchoolDegree ? [
                    'id' => $model->highestSchoolDegree?->id,
                    'name' => $model->highestSchoolDegree?->name,
                ] : null,
                'highestEducationLevel' => $model->highestEducationLevel ? [
                    'id' => $model->highestEducationLevel?->id,
                    'name' => $model->highestEducationLevel?->name,
                ] : null,
                'maritalStatus' => $model->marital_status ?? "single",

                'street' => $model->street,
                'address' => $model->address,
                'secondaryAddress' => $model->secondary_address,
                'zipCode' => $model->zip_code,
                'city' => $model->city,
                'state' => $model->state,
                'country' => $model->country,

                'phone' => $model->phone,
                'mobile' => $model->mobile,
                'email' => $model->email,

                'childrenData' => $model->children_data ?? [],

                'financeDepartmentNumber' => $model->finance_department_number,
                'financeCategory' => $model->finance_category,
                'religion' => $model->religion,
                'religionOfPartner' => $model->religion_of_partner,
                'freeAmountChildren' => $model->free_amount_children,
                'freeAmountYearly' => $model->free_amount_yearly,
                'freeAmountMonthly' => $model->free_amount_monthly,
                'taxLiability' => $model->tax_liability,

                'accountOwner' => $model->account_owner,
                'iban' => $model->iban,
                'bic' => $model->bic,
                'bankName' => $model->bank_name,

                'socialSecurityNumber' => $model->social_security_number,
                'healthInsurance' => $model->health_insurance,
                'previousHealthInsurance' => $model->previous_health_insurance,
                'groupPeople' => $model->group_people,
                'contributionGroup' => $model->contribution_group,

                'accidentInsurance' => $model->accident_insurance,
                'tariffPoints' => $model->tariff_points,
                'percentageAssociation' => $model->percentage_association,
                'privateEmail' => $model->private_email,

                'userId' => $model->user_id,
                'jobData' => $this->showJobData($model->id)->original['data'],
                'compensationAndbenefits' => $this->showCompensationData($model->id)->original['data'],
            ]);
        }
        return response()->json(['message' => 'No profile exists for this user'], 400);
    }
}
