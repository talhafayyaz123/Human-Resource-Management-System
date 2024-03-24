<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeInterviewResource;
use App\Models\EmployeeInterview;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class EmployeeInterviewController extends Controller
{
    use CustomHelper;

    function __construct()
    {
        $this->middleware('check.permission:employee-interview.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:employee-interview.create', ['only' => ['store']]);
        $this->middleware('check.permission:employee-interview.edit', ['only' => ['update']]);
        $this->middleware('check.permission:employee-interview.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $per_page = $request->perPage ?? 25;
        $employee_interviews = new EmployeeInterview();

        if (!$this->hasRole($request, 'admin')) {
            $employee_interviews = $employee_interviews->where(function ($query) use ($request) {
                $query->where('employee_id', $this->getAuthUserId($request));
                $query->orWhere('creator_id', $this->getAuthUserId($request));
            });
        }

        if ($sort_by && $sort_order) {
            $employee_interviews = $this->applySortingBeforePagination($employee_interviews, $sort_by, $sort_order);
        }
        $employee_interviews = $employee_interviews->orderBy('created_at', "ASC")
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($employee_interview) => [
                'id' => $employee_interview->id,
                'employeeId' => $employee_interview->employee_id,
                'creatorId' => $employee_interview->creator_id,
                'creatorName' => $employee_interview->creator?->full_name,
                'createdAt' => Carbon::parse($employee_interview->created_at)->toDateString(),
            ]);
        return response()->json(['interviews' => $employee_interviews]);
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
            'employeeId' => 'required|string',
            'creatorId' => 'required|string',
            'goalAchievement' => 'nullable|string',
            'overallSummary' => 'nullable|string',
            'interviewReason' => 'nullable|string',
            'plannedDate' => 'nullable|date',
            'trainingPersonalDevelopment' => 'nullable|string',
            //personal skills rating validation
            'personalSkills' => 'nullable|array',
            'personalSkills.motivationRating' => 'nullable|numeric|between:1,5',
            'personalSkills.responsibilityRating' => 'nullable|numeric|between:1,5',
            'personalSkills.summary' => 'nullable|string',
            //company values
            'companyValues' => 'nullable|array',
            'companyValues.innovationRating' => 'nullable|numeric|between:1,5',
            'companyValues.customerFocusRating' => 'nullable|numeric|between:1,5',
            'companyValues.openSolutionsRating' => 'nullable|numeric|between:1,5',
            'companyValues.summary' => 'nullable|string',
            //social competence
            'socialCompetence' => 'nullable|array',
            'socialCompetence.communicationRating' => 'nullable|numeric|between:1,5',
            'socialCompetence.teamworkRating' => 'nullable|numeric|between:1,5',
            'socialCompetence.errorCultureRating' => 'nullable|numeric|between:1,5',
            'socialCompetence.summary' => 'nullable|string',
            //professional and technical abilities
            'professionalTechnicalAbilities' => 'nullable|array',
            'professionalTechnicalAbilities.toolsRating' => 'nullable|numeric|between:1,5',
            'professionalTechnicalAbilities.qualityRating' => 'nullable|numeric|between:1,5',
            'professionalTechnicalAbilities.efficiencyRating' => 'nullable|numeric|between:1,5',
            'professionalTechnicalAbilities.summary' => 'nullable|string',
            'professionalTechnicalAbilities.completingProjectAndTask' => 'nullable|numeric|between:1,5',
            //satisfaction with superiors
            'satisfactionWithSuperiors' => 'nullable|array',
            'satisfactionWithSuperiors.communication' => 'nullable|numeric|between:1,5',
            'satisfactionWithSuperiors.rangeOfTasks' => 'nullable|numeric|between:1,5',
            'satisfactionWithSuperiors.workload' => 'nullable|numeric|between:1,5',
            'satisfactionWithSuperiors.reachability' => 'nullable|numeric|between:1,5',
            'satisfactionWithSuperiors.summary' => 'nullable|string',
            //overall satisfaction
            'overallSatisfaction' => 'nullable|array',
            'overallSatisfaction.generalSatisfaction' => 'nullable|numeric|between:1,5',
            'overallSatisfaction.satisfactionWithLevel' => 'nullable|numeric|between:1,5',
            'overallSatisfaction.summary' => 'nullable|string',

            //team fields
            'teamGoalAchievement' => 'nullable|string',
            'teamOverallSummary' => 'nullable|string',
            //team personal skills rating validation
            'teamPersonalSkills' => 'nullable|array',
            'teamPersonalSkills.motivationRating' => 'nullable|numeric|between:1,5',
            'teamPersonalSkills.responsibilityRating' => 'nullable|numeric|between:1,5',
            'teamPersonalSkills.summary' => 'nullable|string',
            //team company values
            'teamCompanyValues' => 'nullable|array',
            'teamCompanyValues.innovationRating' => 'nullable|numeric|between:1,5',
            'teamCompanyValues.customerFocusRating' => 'nullable|numeric|between:1,5',
            'teamCompanyValues.openSolutionsRating' => 'nullable|numeric|between:1,5',
            'teamCompanyValues.summary' => 'nullable|string',
            //team social competence
            'teamSocialCompetence' => 'nullable|array',
            'teamSocialCompetence.communicationRating' => 'nullable|numeric|between:1,5',
            'teamSocialCompetence.teamworkRating' => 'nullable|numeric|between:1,5',
            'teamSocialCompetence.errorCultureRating' => 'nullable|numeric|between:1,5',
            'teamSocialCompetence.summary' => 'nullable|string',
            //team professional and technical abilities
            'teamProfessionalTechnicalAbilities' => 'nullable|array',
            'teamProfessionalTechnicalAbilities.toolsRating' => 'nullable|numeric|between:1,5',
            'teamProfessionalTechnicalAbilities.qualityRating' => 'nullable|numeric|between:1,5',
            'teamProfessionalTechnicalAbilities.efficiencyRating' => 'nullable|numeric|between:1,5',
            'teamProfessionalTechnicalAbilities.summary' => 'nullable|string',
            'teamProfessionalTechnicalAbilities.completingProjectAndTask' => 'nullable|numeric|between:1,5',

            'finalGoalAchievement' => 'nullable|string',
            'finalOverallSummary' => 'nullable|string',
            // final personal skills rating validation
            'finalPersonalSkills' => 'nullable|array',
            'finalPersonalSkills.motivationRating' => 'nullable|numeric|between:1,5',
            'finalPersonalSkills.responsibilityRating' => 'nullable|numeric|between:1,5',
            'finalPersonalSkills.summary' => 'nullable|string',
            // final company values
            'finalCompanyValues' => 'nullable|array',
            'finalCompanyValues.innovationRating' => 'nullable|numeric|between:1,5',
            'finalCompanyValues.customerFocusRating' => 'nullable|numeric|between:1,5',
            'finalCompanyValues.openSolutionsRating' => 'nullable|numeric|between:1,5',
            'finalCompanyValues.summary' => 'nullable|string',
            // final social competence
            'finalSocialCompetence' => 'nullable|array',
            'finalSocialCompetence.communicationRating' => 'nullable|numeric|between:1,5',
            'finalSocialCompetence.teamworkRating' => 'nullable|numeric|between:1,5',
            'finalSocialCompetence.errorCultureRating' => 'nullable|numeric|between:1,5',
            'finalSocialCompetence.summary' => 'nullable|string',
            // final professional and technical abilities
            'finalProfessionalTechnicalAbilities' => 'nullable|array',
            'finalProfessionalTechnicalAbilities.toolsRating' => 'nullable|numeric|between:1,5',
            'finalProfessionalTechnicalAbilities.qualityRating' => 'nullable|numeric|between:1,5',
            'finalProfessionalTechnicalAbilities.efficiencyRating' => 'nullable|numeric|between:1,5',
            'finalProfessionalTechnicalAbilities.summary' => 'nullable|string',
            'finalProfessionalTechnicalAbilities.completingProjectAndTask' => 'nullable|numeric|between:1,5',
        ]);

        $employee_interview = new EmployeeInterview();
        $this->saveData($employee_interview, $request);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Employee interview'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $employee_interview = EmployeeInterview::where('id', $id);
        if (!$this->hasRole($request, 'admin')) {
            $employee_interview = $employee_interview->where(function ($query) use ($request) {
                $query->where('employee_id', $this->getAuthUserId($request));
                $query->orWhere('creator_id', $this->getAuthUserId($request));
            });
        }
        $employee_interview = $employee_interview->first();
        return response()->json(['data' => new EmployeeInterviewResource($employee_interview)]);
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
            'employeeId' => 'required|string',
            'creatorId' => 'required|string',
            'goalAchievement' => 'nullable|string',
            'overallSummary' => 'nullable|string',
            'interviewReason' => 'nullable|string',
            'plannedDate' => 'nullable|string',
            'trainingPersonalDevelopment' => 'nullable|string',
            //personal skills rating validation
            'personalSkills' => 'nullable|array',
            'personalSkills.motivationRating' => 'nullable|numeric|between:1,5',
            'personalSkills.responsibilityRating' => 'nullable|numeric|between:1,5',
            'personalSkills.summary' => 'nullable|string',
            //company values
            'companyValues' => 'nullable|array',
            'companyValues.innovationRating' => 'nullable|numeric|between:1,5',
            'companyValues.customerFocusRating' => 'nullable|numeric|between:1,5',
            'companyValues.openSolutionsRating' => 'nullable|numeric|between:1,5',
            'companyValues.summary' => 'nullable|string',
            //social competence
            'socialCompetence' => 'nullable|array',
            'socialCompetence.communicationRating' => 'nullable|numeric|between:1,5',
            'socialCompetence.teamworkRating' => 'nullable|numeric|between:1,5',
            'socialCompetence.errorCultureRating' => 'nullable|numeric|between:1,5',
            'socialCompetence.summary' => 'nullable|string',
            //professional and technical abilities
            'professionalTechnicalAbilities' => 'nullable|array',
            'professionalTechnicalAbilities.toolsRating' => 'nullable|numeric|between:1,5',
            'professionalTechnicalAbilities.qualityRating' => 'nullable|numeric|between:1,5',
            'professionalTechnicalAbilities.efficiencyRating' => 'nullable|numeric|between:1,5',
            'professionalTechnicalAbilities.summary' => 'nullable|string',
            'professionalTechnicalAbilities.completingProjectAndTask' => 'nullable|numeric|between:1,5',
            //satisfaction with superiors
            'satisfactionWithSuperiors' => 'nullable|array',
            'satisfactionWithSuperiors.communication' => 'nullable|numeric|between:1,5',
            'satisfactionWithSuperiors.rangeOfTasks' => 'nullable|numeric|between:1,5',
            'satisfactionWithSuperiors.workload' => 'nullable|numeric|between:1,5',
            'satisfactionWithSuperiors.reachability' => 'nullable|numeric|between:1,5',
            'satisfactionWithSuperiors.summary' => 'nullable|string',
            //overall satisfaction
            'overallSatisfaction' => 'nullable|array',
            'overallSatisfaction.generalSatisfaction' => 'nullable|numeric|between:1,5',
            'overallSatisfaction.satisfactionWithLevel' => 'nullable|numeric|between:1,5',
            'overallSatisfaction.summary' => 'nullable|string',
            //team fields
            'teamGoalAchievement' => 'nullable|string',
            'teamOverallSummary' => 'nullable|string',
            //team personal skills rating validation
            'teamPersonalSkills' => 'nullable|array',
            'teamPersonalSkills.motivationRating' => 'nullable|numeric|between:1,5',
            'teamPersonalSkills.responsibilityRating' => 'nullable|numeric|between:1,5',
            'teamPersonalSkills.summary' => 'nullable|string',
            //team company values
            'teamCompanyValues' => 'nullable|array',
            'teamCompanyValues.innovationRating' => 'nullable|numeric|between:1,5',
            'teamCompanyValues.customerFocusRating' => 'nullable|numeric|between:1,5',
            'teamCompanyValues.openSolutionsRating' => 'nullable|numeric|between:1,5',
            'teamCompanyValues.summary' => 'nullable|string',
            //team social competence
            'teamSocialCompetence' => 'nullable|array',
            'teamSocialCompetence.communicationRating' => 'nullable|numeric|between:1,5',
            'teamSocialCompetence.teamworkRating' => 'nullable|numeric|between:1,5',
            'teamSocialCompetence.errorCultureRating' => 'nullable|numeric|between:1,5',
            'teamSocialCompetence.summary' => 'nullable|string',
            //team professional and technical abilities
            'teamProfessionalTechnicalAbilities' => 'nullable|array',
            'teamProfessionalTechnicalAbilities.toolsRating' => 'nullable|numeric|between:1,5',
            'teamProfessionalTechnicalAbilities.qualityRating' => 'nullable|numeric|between:1,5',
            'teamProfessionalTechnicalAbilities.efficiencyRating' => 'nullable|numeric|between:1,5',
            'teamProfessionalTechnicalAbilities.summary' => 'nullable|string',
            'teamProfessionalTechnicalAbilities.completingProjectAndTask' => 'nullable|numeric|between:1,5',

            'finalGoalAchievement' => 'nullable|string',
            'finalOverallSummary' => 'nullable|string',
            // final personal skills rating validation
            'finalPersonalSkills' => 'nullable|array',
            'finalPersonalSkills.motivationRating' => 'nullable|numeric|between:1,5',
            'finalPersonalSkills.responsibilityRating' => 'nullable|numeric|between:1,5',
            'finalPersonalSkills.summary' => 'nullable|string',
            // final company values
            'finalCompanyValues' => 'nullable|array',
            'finalCompanyValues.innovationRating' => 'nullable|numeric|between:1,5',
            'finalCompanyValues.customerFocusRating' => 'nullable|numeric|between:1,5',
            'finalCompanyValues.openSolutionsRating' => 'nullable|numeric|between:1,5',
            'finalCompanyValues.summary' => 'nullable|string',
            // final social competence
            'finalSocialCompetence' => 'nullable|array',
            'finalSocialCompetence.communicationRating' => 'nullable|numeric|between:1,5',
            'finalSocialCompetence.teamworkRating' => 'nullable|numeric|between:1,5',
            'finalSocialCompetence.errorCultureRating' => 'nullable|numeric|between:1,5',
            'finalSocialCompetence.summary' => 'nullable|string',
            // final professional and technical abilities
            'finalProfessionalTechnicalAbilities' => 'nullable|array',
            'finalProfessionalTechnicalAbilities.toolsRating' => 'nullable|numeric|between:1,5',
            'finalProfessionalTechnicalAbilities.qualityRating' => 'nullable|numeric|between:1,5',
            'finalProfessionalTechnicalAbilities.efficiencyRating' => 'nullable|numeric|between:1,5',
            'finalProfessionalTechnicalAbilities.summary' => 'nullable|string',
            'finalProfessionalTechnicalAbilities.completingProjectAndTask' => 'nullable|numeric|between:1,5',
        ]);
        $employee_interview = EmployeeInterview::findOrFail($id);
        $this->saveData($employee_interview, $request);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Employee interview'])], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee_interview = EmployeeInterview::findOrFail($id);
        $employee_interview->delete();
        return response()->json(['messages.record_deleted_success', ['name' => 'Employee interview']], 200);
    }


    private function saveData(EmployeeInterview $employee_interview, Request $request): EmployeeInterview
    {
        // Setting records for individual attributes common fields
        $employee_interview->employee_id = $request->input('employeeId');
        $employee_interview->creator_id = $request->input('creatorId');
        $employee_interview->goal_achievement = $request->input('goalAchievement');
        $employee_interview->overall_summary = $request->input('overallSummary');
        $employee_interview->interview_reason = $request->input('interviewReason');
        $employee_interview->planned_date = isset($request->plannedDate) ? Carbon::parse($request->plannedDate) : null;
        $employee_interview->training_personal_development = $request->input('trainingPersonalDevelopment');
        // Setting records for JSON data
        $employee_interview->personal_skills = $request->input('personalSkills');
        $employee_interview->company_values = $request->input('companyValues');
        $employee_interview->social_competence = $request->input('socialCompetence');
        $employee_interview->professional_technical_abilities = $request->input('professionalTechnicalAbilities');
        $employee_interview->satisfaction_with_superiors = $request->input('satisfactionWithSuperiors');
        $employee_interview->overall_satisfaction = $request->input('overallSatisfaction');

        //saving data for team json values
        $employee_interview->team_goal_achievement = $request->input('teamGoalAchievement');
        $employee_interview->team_overall_summary = $request->input('teamOverallSummary');
        $employee_interview->team_personal_skills = $request->input('teamPersonalSkills');
        $employee_interview->team_company_values = $request->input('teamCompanyValues');
        $employee_interview->team_social_competence = $request->input('teamSocialCompetence');
        $employee_interview->team_professional_technical_abilities = $request->input('teamProfessionalTechnicalAbilities');


        // Saving code for final attributes
        $employee_interview->final_goal_achievement = $request->input('finalGoalAchievement');
        $employee_interview->final_overall_summary = $request->input('finalOverallSummary');
        $employee_interview->final_personal_skills = $request->input('finalPersonalSkills');
        $employee_interview->final_company_values = $request->input('finalCompanyValues');
        $employee_interview->final_social_competence = $request->input('finalSocialCompetence');
        $employee_interview->final_professional_technical_abilities = $request->input('finalProfessionalTechnicalAbilities');


        $employee_interview->save();

        return $employee_interview;
    }
}
