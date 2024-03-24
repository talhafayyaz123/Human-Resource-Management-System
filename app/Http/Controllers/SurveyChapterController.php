<?php

namespace App\Http\Controllers;

use App\Helpers\OrderHelper;
use App\Models\GlobalSetting;
use App\Models\Survey;
use App\Models\SurveyChapter;
use App\Models\SurveyQuestion;
use App\Utils\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyChapterController extends Controller
{

    /**
     * Run on instantiate
     */
    public function __construct()
    {
        $this->middleware('check.permission:survey-chapter.create', ['only' => ['store']]);
        $this->middleware('check.permission:survey-chapter.edit', ['only' => ['update', 'moveChapter', 'moveQuestionToChapter']]);
        $this->middleware('check.permission:survey-chapter.delete', ['only' => ['destroy']]);
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
            "title" => "required|string",
            "upperOrder" => "nullable|integer",
        ]);
        $survey_chapter = new SurveyChapter;
        if (isset($request->surveyId)) {
            $survey_chapter->survey_id = $request->surveyId;
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
            $survey_chapter->survey_id = $survey->id;
        }
        $order = OrderHelper::order($request->upperOrder ?? null);
        $survey_chapter->chapter_order = $order;
        $survey_chapter->title = $request->title;
        $survey_chapter->save();
        return response()->json(['message' => 'Chapter has been created', 'id' => $survey_chapter->id, 'surveyId' => $survey_chapter->survey_id, 'chapterOrder' => round($order)], 200);
    }

    /**
     * Update Survey Chapter
     * @param  object $survey_chapter
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $request->validate([
            "title" => "required|string"
        ]);
        $survey_chapter = SurveyChapter::findOrFail($id);
        $survey_chapter->title = $request->title;
        $survey_chapter->save();
        return response()->json(['message' => 'Chapter has been successfully updated'], 200);
    }

    /**
     * Delete Survey Chapter
     * @param  object $survey_chapter
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $survey_chapter = SurveyChapter::findOrFail($id);
        $survey_chapter->delete();
        SurveyQuestion::where('survey_chapter_id', $survey_chapter->id)->delete();
        return response()->json(['message' => 'Chapter has been successfully deleted'], 200);
    }

    /**
     * Move Survey Question on base of order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function moveChapter(Request $request)
    {
        $request->validate([
            "id" => "required",
            "upperOrder" => "nullable|integer",
            "lowerOrder" => "nullable|integer"
        ]);
        $survey_chapter = SurveyChapter::findOrFail($request->id);
        $order = OrderHelper::order(
            $request->upperOrder ?? null,
            $request->lowerOrder ?? null
        );
        $survey_chapter->chapter_order = $order;
        $survey_chapter->save();
        return response()->json(['message' => 'Chapter moved successfully'], 200);
    }
    /**
     * Move Survey Question to chapter
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function moveQuestionToChapter(Request $request)
    {
        $request->validate([
            "questionId" => "required|integer",
            "upperOrder" => "nullable|integer",
            "lowerOrder" => "nullable|integer"
        ]);
        $survey_question = SurveyQuestion::findOrFail($request->questionId);
        $survey_question->survey_chapter_id = $request->chapterId ?? null;
        $order = OrderHelper::order(
            $request->upperOrder ?? null,
            $request->lowerOrder ?? null
        );
        $survey_question->question_order = $order;
        $survey_question->save();
        return response()->json(['message' => is_null($request->chapterId) ? 'Sucessfully moved question out of chapter' : 'Sucessfully moved question to chapter'], 200);
    }
}
