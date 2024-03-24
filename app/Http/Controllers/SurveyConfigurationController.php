<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyConfiguration;
use Illuminate\Http\Request;

class SurveyConfigurationController extends Controller
{
    
    /**
     * Runs when instance is initiated
     */
    public function __construct()
    {
        $this->middleware('check.permission:survey-configuration.list', ['only' => ['index']]);
        $this->middleware('check.permission:survey-configuration.create', ['only' => ['store']]);
        $this->middleware('check.permission:survey-configuration.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $survey_configurations = SurveyConfiguration::where("survey_id", $request->surveyId)->get()->map(fn ($configuration) => [
            "id" => $configuration->id,
            "title" => $configuration->title,
            "isExpertMode" => $configuration->is_expert_mode,
            "route" => $configuration->route,
            "functionName" => $configuration->function_name,
            "code" => $configuration->code,
            "successFeedback" => $configuration->success_feedback,
            "arguments" => json_decode($configuration->arguments),
            "surveyId" => $configuration->survey_id
        ]);
        return response()->json([
            "surveyConfigurations" => $survey_configurations
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->surveyId)) {
            $survey = Survey::findOrFail($request->surveyId);
            if (!empty($survey->configurations)) {
                $survey->configurations()->delete();
            }
        }
        $survey_configurations = $request->surveyConfigurations;
        foreach ($survey_configurations as $configuration) {
            $survey_configuration = new SurveyConfiguration;
            $survey_configuration->survey_id = $configuration["surveyId"];
            $survey_configuration->is_expert_mode = $configuration["isExpertMode"];
            $survey_configuration->title = $configuration["title"];
            $survey_configuration->route = $configuration["route"];
            $survey_configuration->function_name = $configuration["functionName"];
            $survey_configuration->code = $configuration["code"];
            $survey_configuration->success_feedback = $configuration["successFeedback"];
            $survey_configuration->arguments = json_encode($configuration["arguments"]);
            $survey_configuration->save();
        }
        return response()->json(['message' => 'Survey configuration updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $survey_configuration = SurveyConfiguration::findOrFail($id);
        $survey_configuration->delete();
        return response()->json(["message" => "Survey configuration deleted!"]);
    }
}
