<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeInterviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'employeeId' => $this->employee_id,
            'creatorId' => $this->creator_id,
            'creator' => $this->creator?->full_name,
            'goalAchievement' => $this->goal_achievement,
            'overallSummary' => $this->overall_summary,
            'interviewReason' => $this->interview_reason,
            //employee tab values
            'personalSkills' => [
                'motivationRating' => $this->personal_skills['motivationRating'] ?? null,
                'responsibilityRating' => $this->personal_skills['responsibilityRating'] ?? null,
                'summary' => $this->personal_skills['summary'] ?? null,
            ],
            'companyValues' => [
                'innovationRating' => $this->company_values['innovationRating'] ?? null,
                'customerFocusRating' => $this->company_values['customerFocusRating'] ?? null,
                'openSolutionsRating' => $this->company_values['openSolutionsRating'] ?? null,
                'summary' => $this->company_values['summary'] ?? null,
            ],
            'socialCompetence' => [
                'communicationRating' => $this->social_competence['communicationRating'] ?? null,
                'teamworkRating' => $this->social_competence['teamworkRating'] ?? null,
                'errorCultureRating' => $this->social_competence['errorCultureRating'] ?? null,
                'summary' => $this->social_competence['summary'] ?? null,
            ],
            'professionalTechnicalAbilities' => [
                'toolsRating' => $this->professional_technical_abilities['toolsRating'] ?? null,
                'qualityRating' => $this->professional_technical_abilities['qualityRating'] ?? null,
                'efficiencyRating' => $this->professional_technical_abilities['efficiencyRating'] ?? null,
                'summary' => $this->professional_technical_abilities['summary'] ?? null,
                'completingProjectAndTask' => $this->professional_technical_abilities['completingProjectAndTask'] ?? null
            ],
            'satisfactionWithSuperiors' => [
                'communication' => $this->satisfaction_with_superiors['communication'] ?? null,
                'rangeOfTasks' => $this->satisfaction_with_superiors['rangeOfTasks'] ?? null,
                'workload' =>  $this->satisfaction_with_superiors['workload'] ?? null,
                'reachability' =>  $this->satisfaction_with_superiors['reachability'] ?? null,
                'summary' => $this->satisfaction_with_superiors['summary'] ?? null,
            ],
            //overall Satisfaction 
            'overallSatisfaction' => [
                'generalSatisfaction' => $this->overall_satisfaction['generalSatisfaction'] ?? null,
                'satisfactionWithLevel' => $this->overall_satisfaction['satisfactionWithLevel'] ?? null,
                'summary' => $this->overall_satisfaction['summary'] ?? null
            ],
            'trainingPersonalDevelopment' => $this->training_personal_development ?? null,
            //team attributes
            'teamGoalAchievement' => $this->team_goal_achievement ?? null,
            'teamOverallSummary' => $this->team_overall_summary ?? null,
            'teamPersonalSkills' => [
                'motivationRating' => $this->team_personal_skills['motivationRating'] ?? null,
                'responsibilityRating' => $this->team_personal_skills['responsibilityRating'] ?? null,
                'summary' => $this->team_personal_skills['summary'] ?? null,
            ],
            'teamCompanyValues' => [
                'innovationRating' => $this->team_company_values['innovationRating'] ?? null,
                'customerFocusRating' => $this->team_company_values['customerFocusRating'] ?? null,
                'openSolutionsRating' => $this->team_company_values['openSolutionsRating'] ?? null,
                'summary' => $this->team_company_values['summary'] ?? null,
            ],
            'teamSocialCompetence' => [
                'communicationRating' => $this->team_social_competence['communicationRating'] ?? null,
                'teamworkRating' => $this->team_social_competence['teamworkRating'] ?? null,
                'errorCultureRating' => $this->team_social_competence['errorCultureRating'] ?? null,
                'summary' => $this->team_social_competence['summary'] ?? null,
            ],
            'teamProfessionalTechnicalAbilities' => [
                'toolsRating' => $this->team_professional_technical_abilities['toolsRating'] ?? null,
                'qualityRating' => $this->team_professional_technical_abilities['qualityRating'] ?? null,
                'efficiencyRating' => $this->team_professional_technical_abilities['efficiencyRating'] ?? null,
                'summary' => $this->team_professional_technical_abilities['summary'] ?? null,
                'completingProjectAndTask' => $this->team_professional_technical_abilities['completingProjectAndTask'] ?? null
            ],

            //final attributes
            'finalGoalAchievement' => $this->final_goal_achievement ?? null,
            'finalOverallSummary' => $this->final_overall_summary ?? null,
            'finalPersonalSkills' => [
                'motivationRating' => $this->final_personal_skills['motivationRating'] ?? null,
                'responsibilityRating' => $this->final_personal_skills['responsibilityRating'] ?? null,
                'summary' => $this->final_personal_skills['summary'] ?? null,
            ],
            'finalCompanyValues' => [
                'innovationRating' => $this->final_company_values['innovationRating'] ?? null,
                'customerFocusRating' => $this->final_company_values['customerFocusRating'] ?? null,
                'openSolutionsRating' => $this->final_company_values['openSolutionsRating'] ?? null,
                'summary' => $this->final_company_values['summary'] ?? null,
            ],
            'finalSocialCompetence' => [
                'communicationRating' => $this->final_social_competence['communicationRating'] ?? null,
                'teamworkRating' => $this->final_social_competence['teamworkRating'] ?? null,
                'errorCultureRating' => $this->final_social_competence['errorCultureRating'] ?? null,
                'summary' => $this->final_social_competence['summary'] ?? null,
            ],
            'finalProfessionalTechnicalAbilities' => [
                'toolsRating' => $this->final_professional_technical_abilities['toolsRating'] ?? null,
                'qualityRating' => $this->final_professional_technical_abilities['qualityRating'] ?? null,
                'efficiencyRating' => $this->final_professional_technical_abilities['efficiencyRating'] ?? null,
                'summary' => $this->final_professional_technical_abilities['summary'] ?? null,
                'completingProjectAndTask' => $this->final_professional_technical_abilities['completingProjectAndTask'] ?? null
            ],
            'plannedDate' => isset($this->planned_date) ? Carbon::parse($this->planned_date)->toDateString() : null,
            'createdAt' => Carbon::parse($this->created_at)->toDateString(),
            'updatedAtLatest' => Carbon::parse($this->updatedAt)->toDateString()
        ];
    }
}
