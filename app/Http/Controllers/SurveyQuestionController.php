<?php

namespace App\Http\Controllers;

use App\Enums\QuestionType;
use App\Helpers\OrderHelper;
use App\Models\GlobalSetting;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Facades\App\Services\SurveyQuestionService;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyQuestionController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string",
            "chapterId" => "nullable|integer",
            "surveyId" => "nullable|string",
            "upperOrder" => "nullable|integer",
        ]);
        $survey_question = new SurveyQuestion;
        $survey_question->title = $request->title;
        $survey_question->survey_chapter_id = $request->chapterId ?? null;
        if (isset($request->surveyId)) {
            $survey_question->survey_id = $request->surveyId;
        } else {
            $survey = new Survey;
            $global_survey_setting = GlobalSetting::where('key', 'survey')->first();
            if (empty($global_survey_setting)) {
                $global_setting = new GlobalSetting;
                $global_setting->key = 'survey';
                $global_setting->value = 1000;
                $global_setting->save();
            } else {
                $global_setting = tap(DB::table('global_settings')->where('key', 'survey'))->update([
                    'value' => DB::raw("value+1")
                ])->first();
            }
            $survey->survey_number = 'DH' . date("Y") . '-' . $global_setting->value;
            $survey->name = $request->name;
            $survey->save();
            $survey_question->survey_id = $survey->id;
        }
        $order = OrderHelper::order($request->upperOrder ?? null);
        $survey_question->question_order = $order;
        $survey_question->save();
        return response()->json(['message' => 'Question has been created', 'surveyId' => $survey_question->survey_id, 'id' => $survey_question->id, 'questionOrder' => round($order)], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {
        $request->validate([
            "title" => "required|string",
            "descirption" => "nullable|string",
            'chapterId' => 'nullable|integer',
            'configuration' => 'array|nullable',
            'configuration.type' => ['required', Rule::in(QuestionType::ALL)],
            'configuration.groups' => 'nullable|array',
            'configuration.groups.*.title' => 'required|distinct',
            'configuration.groups.*.type' => 'required',
            'configuration.groups.*.options' => 'nullable|array',
            'configuration.groups.*.options.*.title' => 'required|distinct',
            'configuration.conditions' => 'nullable|array',
            'configuration.conditions.*.next' => 'nullable|integer'
        ]);
        $survey_question = SurveyQuestion::findOrFail($id);
        SurveyQuestionService::updateSurveyQuestion($survey_question, $request->all());
        return response()->json(['message' => 'Question has been updated'], 200);
    }

    /**
     * Move survey question
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function moveQuestion(Request $request)
    {
        $request->validate([
            "id" => "required",
            "upperOrder" => "nullable|integer",
            "lowerOrder" => "nullable|integer"
        ]);
        $survey_question = SurveyQuestion::findOrFail($request->id);
        $order = OrderHelper::order(
            $request->upperOrder ?? null,
            $request->lowerOrder ?? null
        );
        $survey_question->question_order = $order;
        $survey_question->save();
        return response()->json(['message' => 'Question moved successfully'], 200);
    }

    /**
     * Delete survey question
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $survey_question = SurveyQuestion::findOrFail($id);
        $survey_question->delete();
        return response()->json(['message' => 'Question has been deleted'], 200);
    }
}
