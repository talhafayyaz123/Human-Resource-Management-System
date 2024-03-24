<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Models\Product;
use App\Models\Survey;
use App\Models\SurveyConfiguration;
use App\Models\GlobalSetting;
use App\Traits\CustomHelper;
use App\Utils\Logger;
use Facades\App\Services\SurveyQuestionService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    use CustomHelper;

    /**
     * Get listing of surveys
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $surveys = new Survey;
        if ($sort_by && $sort_order) {
            $surveys = $this->applySortingBeforePagination($surveys, $sort_by, $sort_order);
        }
        $surveys = $surveys->paginate(10);
        $survey_list = $surveys->map(function ($survey) {
            return [
                'id' => $survey->id,
                'name' => $survey->name,
                'surveyNumber' => $survey->survey_number,
                'questionCount' => isset($survey->questions) ? $survey->questions->count() : 0
            ];
        });

        return response()->json([
            'data' => $survey_list, 'links' => $surveys->links(),
            'meta' => [
                'current_page' => $surveys->currentPage(),
                'from' => $surveys->firstItem(),
                'last_page' => $surveys->lastPage(),
                'path' => request()->url(),
                'per_page' => $surveys->perPage(),
                'to' => $surveys->lastItem(),
                'total' => $surveys->total(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'products' => Product::orderBy('name')
                ->paginate(10)
                ->through(fn ($product) => [
                    'id' => $product->id,
                    'articleNumber' => $product->article_number,
                    'name' => $product->name,
                    'listingPrice' => $product->listing_price,
                    'status' => $product->status ? 'active' : 'deactive',
                    'salePrice' => $product->sale_price,
                    'profit' => $product->profit,
                    'discount' => $product->discount,
                    'quantity' => 1,
                    'tax' => $product->tax,
                ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $survey = new Survey;
        $global_survey_setting = GlobalSetting::where('key', 'survey')->where('year', date("Y"))->first();
        if (empty($global_survey_setting)) {
            $global_setting = new GlobalSetting;
            $global_setting->key = 'survey';
            $global_setting->value = 1000;
            $global_setting->year = date("Y");
            $global_setting->save();
        } else {
            $global_setting = tap(DB::table('global_settings')->where('key', 'survey')->where('year', date("Y")))->update([
                'value' => DB::raw("value+1")
            ])->first();
        }
        $survey->survey_number = 'DH' . date("Y") . '-' . $global_setting->value;
        $survey->is_manual_products = $request->addProductsManually;
        $survey->minimize_cart = $request->minimizeCart;
        $survey->minimize_steps = $request->minimizeSteps;
        $survey->save();
        $content = [
            'moduleAction' => 'createSurvey',
            'data' => $survey->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => 'Survey created successfully', 'surveyId' => $survey->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $survey = Survey::with('surveyStyleConfigurations')->findOrFail($id);
        $chapters = $survey->chapters;
        $questions = $survey->questions->whereNull('survey_chapter_id');
        $chapters_collect = $chapters->map(function ($chapter) {
            return [
                'chapterId' => $chapter->id,
                'chapterTitle' => $chapter->title,
                'order' => $chapter->chapter_order,
                'questions' => isset($chapter->questions) ? SurveyQuestionService::mapQuestionArray(
                    $chapter->questions->sortBy('question_order')->values()
                ) : []
            ];
        });
        $questions_collect = SurveyQuestionService::mapQuestionArray($questions);
        $questions_and_chapters = $chapters_collect->concat($questions_collect);
        return response()->json([
            // 'manualProducts' => $survey->globalConfigurationProducts ?? [], leave for now
            'surveyNumber' => $survey->survey_number,
            'name' => $survey->name,
            'isManualProducts' => $survey->is_manual_products,
            'minimizeCart' => $survey->minimize_cart,
            'minimizeSteps' => $survey->minimize_steps,
            'stylesConfiguration' => isset($survey->surveyStyleConfigurations) ? [
                'steps' => $survey->surveyStyleConfigurations->steps,
                'questionDetails' => $survey->surveyStyleConfigurations->question_details,
                'cart' => $survey->surveyStyleConfigurations->cart,
                'productDetails' => $survey->surveyStyleConfigurations->product_details,
                'layout' => $survey->surveyStyleConfigurations->layout
            ] : [],
            'questionsAndChapters' => $questions_and_chapters->sortBy('order')->values()
            /*'questionsWithoutChapter' => $questions_collect->values() ?? [],
            'questionsWithChapter' => $chapters_collect->values() ?? [] */
        ]);
    }

    /**
     * Store css styles of survey
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeStyleConfigurations(Request $request)
    {
        $request->validate([
            'surveyId' => 'required|exists:surveys,id',
            'steps' => 'required|array',
            'steps.cardBgColor' => 'required',
            'steps.cardTextColor' => 'required',
            'steps.cardHeaderBgColor' => 'required',
            'steps.cardHeaderTextColor' => 'required',
            'questionDetails' => 'required|array',
            'questionDetails.cardBgColor' => 'required',
            'questionDetails.cardTextColor' => 'required',
            'cart' => 'required|array',
            'cart.cardBgColor' => 'required',
            'cart.cardTextColor' => 'required',
            'cart.cardHeaderBgColor' => 'required',
            'cart.cardHeaderTextColor' => 'required',
            'productDetails' => 'required|array',
            'productDetails.cardBgColor' => 'required',
            'productDetails.cardTextColor' => 'required',
            'productDetails.cardHeaderBgColor' => 'required',
            'productDetails.cardHeaderTextColor' => 'required',
            'layout' => 'required'
        ]);
        $survey = Survey::findOrFail($request->surveyId);
        $config = $survey->surveyStyleConfigurations()->firstOrNew([]);
        $config->steps = $request->steps;
        $config->question_details = $request->questionDetails;
        $config->cart = $request->cart;
        $config->product_details = $request->productDetails;
        $config->layout = $request->layout;
        $config->survey_id = $survey->id;
        $config->save();
        return response()->json(['message' => "Survey styles saved", "surveyId" => $survey->surveyId]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Inertia::render('Surveys/Edit', []);
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
            'addProductsManually' => 'required|boolean',
            'minimizeCart' => 'required|boolean',
            'name' => 'required|string'
        ]);
        $survey = Survey::findOrFail($id);
        $survey->is_manual_products = $request->addProductsManually;
        $survey->minimize_cart = $request->minimizeCart;
        $survey->name = $request->name;
        $survey->minimize_steps = $request->minimizeSteps;
        $survey->save();
        $content = [
            'moduleAction' => 'updateSurvey',
            'data' => $survey->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => 'Survey is updated successfully', 'surveyId' => $survey->id]);
    }

    /**
     * Add custom products in survey
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function addManualProducts(Request $request, $id)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required',
            'products.*.quantity' => 'required'
        ]);
        $survey = Survey::findOrFail($id);
        SurveyQuestionService::addManualProducts($survey, $request->products);
        return response()->json(['message' => 'Custom products successfully added', 'surveyId' => $survey->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->delete();
        $content = [
            'moduleAction' => 'deleteSurvey',
            'data' => $survey->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => 'Survey deleted successfully'], 200);
    }


    /**
     * Retrieve all survey products related to a given ID.
     *
     * @param  string  $id The ID to retrieve survey products for.
     * @return \Illuminate\Http\JsonResponse The survey products as a JSON response.
     */
    public function getAllSurveyProducts($id)
    {
        $merged_products = collect();
        $survey = Survey::findOrFail($id);
        $survey_questions = $survey->questions ?? [];

        if ($survey_questions->isNotEmpty()) {
            $optional_products = collect();
            $conditional_products = collect();

            foreach ($survey_questions as $question) {
                $configurator = $question->configurator;
                if ($configurator) {
                    foreach ($configurator->groups as $group) {
                        foreach ($group->options as $option) {
                            $optional_products = $option->products ?? collect();
                            $merged_products = $merged_products->merge($optional_products);
                        }
                    }
                    foreach ($configurator->conditions as $condition) {
                        $conditional_products = $condition->products ?? collect();
                        $merged_products = $merged_products->merge($conditional_products);
                    }
                }
            }
        }
        $merged_products = $merged_products
            ->unique('id')
            ->map(function ($product) {
                return [
                    'id' => $product['id'],
                    'articleNumber' => $product['article_number'] ?? null,
                    'name' => $product['name'] ?? null,
                    'listingPrice' => $product['listing_price'] ?? null,
                    'status' => $product['status'] ? 'active' : 'deactive',
                    'salePrice' => $product['sale_price'] ?? null,
                    'profit' => $product['profit'] ?? null,
                    'discount' => $product['discount'] ?? null,
                    'quantity' => 1,
                    'tax' => $product['tax'] ?? null,
                    'description' => $product['description'] ?? null,
                    'unit' => $product['productUnit'] ? $product['productUnit']['name'] : null,
                ];
            });
        return response()->json(['products' => $merged_products->values()]);
    }
}
