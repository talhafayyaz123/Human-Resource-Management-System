<?php

namespace App\Http\Controllers;

use App\Models\TravelExpenseReportTransportation;
use App\Resources\TravelExpenseTransportationResource;
use Illuminate\Http\Request;

class TravelExpenseReportTransportationController extends Controller
{

    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $request->validate([
            'travelExpenseId' => 'required|exists:travel_expenses,id'
        ]);
        $transportations = TravelExpenseReportTransportation::where('travel_expense_id', $request->travelExpenseId)->get();
        return response()->json(["data" => TravelExpenseTransportationResource::collection($transportations)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'travelExpenseId' => 'required|exists:travel_expenses,id',
            "data" => "required|array",
            'data.*.drivenKilometers' => 'required|int',
            'data.*.carType' => 'required|in:private-car,fleet-car',
            'data.*.amount' => 'required:int',
            'data.*.fleetCarId' => 'required_if:carType,==,fleet-car',
        ], [
            'data.*.drivenKilometers.required' => 'The driven kilometer field is required.',
            'data.*.drivenKilometers.int' => 'The driven kilometer must be a integer value.',
            'data.*.carType.required' => 'The car type field is required.',
            'data.*.amount.required' => 'The amount field is required.',
            'data.*.fleetCarId.required_if' => 'The fleetCarId field is required when car type is private',
        ]);
        $message = 'Travel Expense transportations saved successfully';
        TravelExpenseReportTransportation::where('travel_expense_id', $request->travelExpenseId)->delete();

        foreach ($request->data as $transportation) {
            TravelExpenseReportTransportation::create([
                'driven_kilometer' => @$transportation['drivenKilometers'],
                'car_type' => @$transportation['carType'],
                'amount' => @$transportation['amount'],
                'fleet_car_id' => @$transportation['fleetCarId'],
                'travel_expense_id' => $request->travelExpenseId,
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function destroy($id)
    {
        $tranportation = TravelExpenseReportTransportation::with('travelExpense')->where('id', $id)->first();
        if ($tranportation && $tranportation->travelExpense) {
            $status = $this->getStatus($tranportation->travelExpense);
            if ($status['status'] == 'pending' && $status['approvedCount'] == 0) {
                $tranportation->delete();
                return response()->json([
                    'success' => true,
                    'message' => "Transportation deleted successfully",
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => "One of the approver have already reviewed this request",
            ], 400);
        }
        return response()->json([
            'success' => false,
            'message' => "Invalid id selected",
        ], 400);
    }

    private function getStatus($travelExpense)
    {
        $approvedCount = 0;
        $status = 'pending';
        if (!empty($travelExpense->travelExpenseApprovers->toArray())) {
            if ($travelExpense->travelExpenseApprovers?->count() == $travelExpense->travelExpenseApprovers?->where('status', 'approved')->count()) {
                $status = 'approved';
            } elseif ($travelExpense->travelExpenseApprovers?->where('status', 'rejected')->count() > 0) {
                $status = 'rejected';
            } else {
                $approvedCount = $travelExpense->travelExpenseApprovers?->where('status', 'approved')->count();
                $status = 'pending';
            }
        }
        return [
            'status' => $status,
            'approvedCount' => @$approvedCount
        ];
    }
}
