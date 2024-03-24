<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionCondition;
use App\Models\SurveyQuestionConditionOption;
use App\Models\SurveyQuestionConfigurator;
use App\Models\SurveyQuestionGroup;
use App\Models\SurveyQuestionOption;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SurveyQuestionService
{

    public function updateSurveyQuestion(SurveyQuestion $survey_question, array $fields): SurveyQuestion
    {
        DB::transaction(function () use (&$survey_question, $fields) {
            $survey_question->title = $fields['title'];
            $survey_question->next_question_id = $fields['next'] ?? null;
            $survey_question->text = $fields['text'] ?? '';
            $survey_question->description = $fields['description'] ?? '';
            $survey_question->product_details = $fields['productDetails'] ?? '';
            $survey_question->survey_chapter_id = $fields['chapterId'] ?? null;
            $survey_question->save();
            $configurator = $fields['configuration'] ?? false;
            if ($configurator) {
                //optimization needed
                if (!empty($survey_question->configurator)) {
                    $survey_question->configurator()->delete();
                }
                //optimization needed
                $survey_configurator = SurveyQuestionConfigurator::firstOrNew(['type' => $configurator['type'], 'survey_question_id' => $survey_question->id]);
                $survey_question->configurator()->save($survey_configurator);
                if ($configurator['groups'] ?? false) {
                    //saving groups related to a specific question
                    foreach ($configurator['groups'] as $group) {
                        $survey_question_group = SurveyQuestionGroup::firstOrNew(['title' => $group['title'], 'survey_question_configurator_id' => $survey_configurator->id]);
                        $survey_question_group->type = $group['type'] ?? null;
                        $survey_question_group->title = $group['title'] ?? null;
                        $survey_configurator->groups()->save($survey_question_group);
                        if ($group['options'] ?? false) {
                            foreach ($group['options'] as $option) {
                                $survey_question_option = SurveyQuestionOption::firstOrNew(['title' => $option['title'], 'survey_question_group_id' => $survey_question_group->id]);
                                $survey_question_option->uuid = $option['uuid'] ?? null;
                                $survey_question_option->min = $option['min'] ?? null;
                                $survey_question_option->max = $option['max'] ?? null;
                                $survey_question_option->max = $option['max'] ?? null;
                                $survey_question_option->step = $option['step'] ?? null;
                                $survey_question_option->next_question_id = $option['next'] ?? null;
                                $survey_question_option->placeholder = $option['placeholder'] ?? '';
                                $survey_question_option->type = $option['type'] ?? null;
                                $survey_question_group->options()->save($survey_question_option);
                                if (isset($option['products'])) {
                                    $survey_question_option->products()->sync($this->arrangeProductsArray($option['products'], true));
                                }
                            }
                            $survey_configurator->groups()->save($survey_question_group);
                        }
                    }
                }

                if ($configurator['conditions'] ?? false) {
                    //deleting the previous condition
                    if (isset($survey_configurator->conditions)) {
                        $survey_configurator->conditions()->delete();
                    }
                    foreach ($configurator['conditions'] as $condition) {
                        $survey_question_condition = new SurveyQuestionCondition;
                        $survey_question_condition->discount = $condition['discount'] ?? '';
                        $survey_question_condition->next_question_id = $condition['next'] ?? null;
                        $survey_configurator->conditions()->save($survey_question_condition);
                        if (isset($condition['products'])) {
                            $survey_question_condition->products()->sync($this->arrangeProductsArray($condition['products'], false));
                        }
                        if (isset($condition['options'])) {
                            foreach ($condition['options'] as $option) {
                                if (isset($option['option']['uuid'])) {
                                    $latest_option = SurveyQuestionOption::where('uuid', '=', $option['option']['uuid'])->latest()->firstOrFail();
                                    $survey_question_condition_option = new SurveyQuestionConditionOption();
                                    $survey_question_condition_option->option_id = $latest_option->id;
                                    $survey_question_condition_option->condition = $option['condition'];
                                    $survey_question_condition_option->value = $option['value'] ?? '';
                                    $survey_question_condition_option->operator = $option['operator'];
                                    $survey_question_condition_option->next_question_id = $condition['next'] ?? null;
                                    $survey_question_condition->conditionOptions()->save($survey_question_condition_option);
                                }
                            }
                        }
                    }
                }
            }
        });

        return $survey_question;
    }

    //image updated question
    public function uploadSurveyImage(SurveyQuestion $survey_question, array $fields): SurveyQuestion
    {
        $configurator = $fields['configuration'] ?? false;
        if ($configurator) {
            //optimization needed
            if (!empty($survey_question->configurator)) {
                $survey_question->configurator()->delete();
            }
            //optimization needed
            $survey_configurator = SurveyQuestionConfigurator::firstOrNew(['type' => $configurator['type'], 'survey_question_id' => $survey_question->id]);
            $survey_question->configurator()->save($survey_configurator);
            if ($configurator['options'] ?? false) {
                //saving options related to a specific question
                foreach ($configurator['options'] as $option) {
                    foreach ($option['files'] as $file) {
                        if (isset($file)) {
                            $original_name = $file->getClientOriginalName();
                            $file_name_to_store =
                                $survey_question->id . '__' . $original_name;
                            Storage::disk('local')->put('survey_question/files/', $file, $file_name_to_store);
                            $survey_question_option = new SurveyQuestionOption;
                            $survey_question_option->file_name = $file_name_to_store;
                            $survey_configurator->options()->save($survey_question_option);
                        }
                    }
                }
            }
        }

        return $survey_question;
    }

    public function addManualProducts(Survey $survey, array $products)
    {
        $arranged_products = $this->arrangeProductsArray($products);
        $survey->globalConfigurationProducts()->sync($arranged_products);
        return $survey;
    }

    private function arrangeProductsArray(array $products, $is_optional_products = false)
    {
        $parent_array = [];
        if ($is_optional_products) {
            foreach ($products as $product) {
                if (isset($product['id']))
                    $parent_array[$product['id']] = ['quantity' => $product['quantity']];
            }
        } else {
            foreach ($products as $product) {
                if (isset($product['id']))
                    $parent_array[$product['id']] = ['quantity' => $product['quantity']];
            }
        }
        return $parent_array;
    }

    public function mapQuestionArray($questions)
    {
        return $questions->map(function ($question) {
            $configurator = $question->configurator ?? '';
            return [
                'id' => $question->id,
                'title' => $question->title,
                'text' => $question->text ?? '',
                'order' => $question->question_order,
                'description' => $question->description ?? '',
                'productDetails' => $question->product_details ?? '',
                'chapterId' => $question->chapter->id ?? null,
                'chapterName' => $question->chapter->title ?? '',
                'configuration' => [
                    'type' => $configurator->type ?? 'single-select',
                    'groups' => isset($configurator->groups) ? $configurator->groups->map(function ($group) {
                        return [
                            'id' => $group->id,
                            'uuid' => $group->uuid,
                            'title' => $group->title,
                            'type' => $group->type,
                            'options' => isset($group->options) ? $group->options->map(function ($option) {
                                return [
                                    'id' => $option->id,
                                    'uuid' => $option->uuid,
                                    'title' => $option->title,
                                    'min' => $option->min ?? '',
                                    'max' => $option->max ?? '',
                                    'step' => $option->step ?? '',
                                    'next' => $option->next_question_id ?? '',
                                    'placeholder' => $option->placeholder ?? '',
                                    'type' => $option->type,
                                    'products' => isset($option->products) ? $option->products->map(function ($product) {
                                        return [
                                            'id' => $product->id,
                                            'quantity' => $product->pivot->quantity,
                                        ];
                                    }) : []
                                ];
                            }) : [],
                        ];
                    }) : [],
                    'conditions' => isset($configurator->conditions) ? $configurator->conditions->map(function ($condition) {
                        return [
                            'id' => $condition->id,
                            'discount' => $condition->discount,
                            'next' => $condition->next_question_id ?? '',
                            'options' => isset($condition->conditionOptions) ? $condition->conditionOptions->map(function ($option) {
                                return [
                                    'id' => $option->id,
                                    'condition' => $option->condition ?? '',
                                    'option' => $option->option_id,
                                    'operator' => $option->operator ?? '',
                                    'value' => $option->value ?? '',
                                ];
                            }) : [],
                            'products' => isset($condition->products) ? $condition->products->map(function ($product) {
                                return [
                                    'id' => $product->id,
                                    'quantity' => $product->pivot->quantity
                                ];
                            }) : []

                        ];
                    }) : []
                ],
            ];
        });
    }
}
