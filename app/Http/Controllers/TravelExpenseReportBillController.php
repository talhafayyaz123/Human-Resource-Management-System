<?php

namespace App\Http\Controllers;

use App\Models\TravelExpense;
use App\Models\TravelExpenseReportBill;
use App\Models\UploadedFile;
use App\Resources\TravelExpenseBillResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TravelExpenseReportBillController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'travelExpenseId' => 'required|exists:travel_expenses,id'
        ]);
        $bills = TravelExpenseReportBill::where('travel_expense_id', $request->travelExpenseId)->get();
        return response()->json(["data" => TravelExpenseBillResource::collection($bills)]);
    }

    public function store(Request $request)
    {
        $travelExpense = TravelExpense::with('bills')->where('id', $request->travelExpenseId)->first();
        $request->validate([
            'travelExpenseId' => 'required|int|exists:travel_expenses,id',
            'invoiceTypeId' => 'required|int|exists:travel_expense_invoice_types,id',
            'location' => 'nullable|string',
            'fromDate' => 'required|date|date_format:Y-m-d|after_or_equal:' . $travelExpense->from_date .
                '|before_or_equal:' . $travelExpense->to_date,
            'toDate' => 'required|date|date_format:Y-m-d|after_or_equal:fromDate|before_or_equal:' . $travelExpense->to_date,
            'description' => 'required',
            'attachments' => 'nullable|array',
        ]);

        $bill = new TravelExpenseReportBill();
        $bill->invoice_type_id = $request->invoiceTypeId;
        $bill->invoice_type = $request->invoiceTypeId;
        $bill->location = $request->location;
        $bill->from_date = $request->fromDate;
        $bill->to_date = $request->toDate;
        $bill->description = $request->description;
        $bill->travel_expense_id = $request->travelExpenseId;
        $bill->save();

        if (isset($request['attachments']) && count($request['attachments']) > 0) {
            foreach ($request['attachments'] as $file) {
                $originalName = $file->getClientOriginalName();
                $size = $file->getSize();
                $storageName = preg_replace('/\s+/', '', microtime() . '_' . $originalName);
                Storage::disk('local')->putFileAs('/travelExpense/', $file, $storageName);
                $uploaded_file = new UploadedFile;
                $uploaded_file->storage_name = $storageName;
                $uploaded_file->viewable_name = $originalName;
                $uploaded_file->storage_size =  $size;
                $uploaded_file->fileable()->associate($bill);
                $uploaded_file->save();
            }
        }
        return response()->json(['message' => "Bill added successfully successfully", 'billId' => $bill->id], 200);
    }

    public function update(Request $request, $id)
    {
        $travelExpense = TravelExpense::with('bills')->where('id', $request->travelExpenseId)->first();
        $request->validate([
            'travelExpenseId' => 'required|int|exists:travel_expenses,id',
            'invoiceTypeId' => 'required|int|exists:travel_expense_invoice_types,id',
            'location' => 'nullable|string',
            'fromDate' => 'required|date|date_format:Y-m-d|after_or_equal:' . $travelExpense->from_date .
                '|before_or_equal:' . $travelExpense->to_date,
            'toDate' => 'required|date|date_format:Y-m-d|after_or_equal:fromDate|before_or_equal:' . $travelExpense->to_date,
            'description' => 'required',
            'attachments' => 'nullable|array',
        ]);

        $bill = TravelExpenseReportBill::findOrFail($id);
        $bill->invoice_type_id = $request->invoiceTypeId;
        $bill->invoice_type = $request->invoiceTypeId;
        $bill->location = $request->location;
        $bill->from_date = $request->fromDate;
        $bill->to_date = $request->toDate;
        $bill->description = $request->description;
        $bill->travel_expense_id = $request->travelExpenseId;
        $bill->save();

        $new_files_collection = collect(isset($request['attachments']) ? $request['attachments'] : []);

        $files_to_be_deleted = $bill->attachments->whereNotIn('storage_name', $new_files_collection->pluck('storage_name'));

        $files_to_be_added = $new_files_collection->whereNotIn('storage_name', $bill->attachments->pluck('storage_name'));

        if (!empty($files_to_be_deleted)) {
            foreach ($files_to_be_deleted as $delete_file) {
                if (Storage::exists('travelExpense/' . $delete_file['storage_name'])) {
                    UploadedFile::find($delete_file['id'])->delete();
                    Storage::delete('travelExpense/' . $delete_file['storage_name']);
                }
            }
        }

        foreach ($files_to_be_added as $file) {
            $originalName = $file->getClientOriginalName();
            $size = $file->getSize();
            $storageName = preg_replace('/\s+/', '', microtime() . '_' . $originalName);
            Storage::disk('local')->putFileAs('/travelExpense/', $file, $storageName);
            $uploaded_file = new UploadedFile;
            $uploaded_file->storage_name = $storageName;
            $uploaded_file->viewable_name = $originalName;
            $uploaded_file->storage_size =  $size;
            $uploaded_file->fileable()->associate($bill);
            $uploaded_file->save();
        }

        return response()->json(['message' => "Bill updated successfully successfully", 'billId' => $bill->id], 200);
    }

    public function destroy($id)
    {
        $bill = TravelExpenseReportBill::with('travelExpense')->where('id', $id)->first();
        if ($bill && $bill->travelExpense) {
            $status = $this->getStatus($bill->travelExpense);
            if ($status['status'] == 'pending' && $status['approvedCount'] == 0) {
                $bill->delete();
                return response()->json([
                    'success' => true,
                    'message' => "Bill deleted successfully",
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
