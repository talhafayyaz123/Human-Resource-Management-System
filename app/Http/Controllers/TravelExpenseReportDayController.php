<?php

namespace App\Http\Controllers;

use App\Models\TravelExpenseReportDay;
use App\Resources\TravelExpenseDayResource;
use Illuminate\Http\Request;

class TravelExpenseReportDayController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request){
        $request->validate([
            'travelExpenseId' => 'required|exists:travel_expenses,id'
        ]);
        $days = TravelExpenseReportDay::where('travel_expense_id', $request->travelExpenseId)->get();
        return response()->json(["data" => TravelExpenseDayResource::collection($days)]);
    }

    public function update(Request $request){
        $request->validate([
            'travelExpenseId' => 'required|exists:travel_expenses,id',
            'data' => 'required|array',
            'data.*.id' => 'required|exists:travel_expense_report_days,id',
            'data.*.isActive' => 'required|boolean',
            'data.*.breakfast' => 'required|boolean',
            'data.*.lunch' => 'required|boolean',
            'data.*.dinner' => 'required|boolean',
            'data.*.expenseRate' => 'required|numeric',
        ],[
            'data.*.id.required' => 'The id field is required.',
            'data.*.id.exists' => 'The id is invalid.',
            'data.*.isActive.required' => 'The is active field is required.',
            'data.*.isActive.int' => 'The is active value should be true or false.',
            'data.*.date.date_format' => 'The date format should be Y-m-d.',
            'data.*.breakfast.required' => 'The breakfast field is required.',
            'data.*.breakfast.boolean' => 'The breakfast value should be true or false.',
            'data.*.lunch.required' => 'The lunch field is required.',
            'data.*.lunch.boolean' => 'The lunch value should be true or false.',
            'data.*.dinner.required' => 'The dinner field is required.',
            'data.*.dinner.boolean' => 'The dinner value should be true or false.',
            'data.*.expenseRate.required' => 'The expense rate is required.',
            'data.*.expenseRate.numeric' => 'The expense rate should b numeric.',
        ]);

        foreach ($request->data as $day){
            $reportDay = TravelExpenseReportDay::where('id', $day['id'])->first();
            if ($reportDay){
                $reportDay->is_active = $day['isActive'] ? 1 : 0;
                $reportDay->breakfast = $day['breakfast'] ? 1 : 0;
                $reportDay->lunch = $day['lunch'] ? 1 : 0;
                $reportDay->dinner = $day['dinner'] ? 1 : 0;
                $reportDay->from_time = @$day['fromTime'];
                $reportDay->to_time = @$day['toTime'];
                $reportDay->expense_rate = @$day['expenseRate'];
                $reportDay->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Days specifications updated successfully'
        ]);
    }
}
