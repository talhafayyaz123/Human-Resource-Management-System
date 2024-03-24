<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\Invoice;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OpenPostsController extends Controller
{
    use CustomHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = !empty($request->perPage) ? $request->perPage : 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new Invoice;
        if ($request->customerId) {
            $model = $model->where('company_id', $request->customerId);
        }
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $invoice = $model->orderBy('invoices.created_at')

            ->whereNot('invoice_type', InvoiceType::INVOICE_STORNO)
            ->where('due_date', '<', Carbon::now())
            ->where([
                [
                    'invoices.status', '!=', InvoiceStatus::PAID
                ],
                [
                    'invoices.status', '!=', InvoiceStatus::DRAFT
                ]
            ])
            ->paginate($per_page)
            ->withQueryString()
            ->through(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoiceNumber' => $invoice->invoice_number,
                    'company' => $invoice->company->company_name,
                    'notes' => $invoice->custom_notes_field,
                    'invoiceType' => $invoice->invoice_type,
                    'invoicePeriod' => $invoice->paymentPeriod?->billing_cycle,
                    "status" => $invoice->status,
                    "netto" => $invoice->total_amount_without_tax ?? 0,
                    "reminderSent" => $invoice->reminder_sent,
                    'dueDate' => Carbon::parse($invoice->due_date)->toDateString(),
                    'createdAt' => Carbon::parse($invoice->created_at)->toDateString(),
                    'performanceRecordId' => $invoice->performanceRecord?->id,
                    'invoiceEmail' => $invoice->company?->invoice_email ?? "",
                    'category' => $invoice->invoice_category,
                    'startDate' => Carbon::parse($invoice->start_date)->toDateString(),
                    'endDate' => Carbon::parse($invoice->end_date)->toDateString(),
                    'draftStatusChangeDate' => Carbon::parse($invoice->draft_status_change_date)->toDateString(),
                    'externalOrderNumber' => $invoice->external_order_number,
                    'reminderLevelId' => $invoice->reminder_level_id,
                    'reminderLevel' => $invoice->invoiceReminderLevel?->level_name ?? '',
                    'reminderStopUntil' => $invoice->reminder_stop_until ? Carbon::parse($invoice->reminder_stop_until)->toDateString() : null,
                    'companyDetails' => $invoice->company ? [
                        'id' => $invoice->company?->id,
                        'companyName' => $invoice->company?->company_name,
                        'vatId' => $invoice->company?->vat_id,
                        'url' => $invoice->company?->url,
                        'type' => $invoice->company?->type,
                        'customerType' => $invoice->company?->customer_type,
                        'companyNumber' => $invoice->company?->company_number,
                        'state' => $invoice->company?->headQuarters?->state,
                        'city' => $invoice->company?->headQuarters?->city,
                        'country' => $invoice->company?->headQuarters?->country,
                        'address' => $invoice->company?->headQuarters?->address_first,
                        'code' => $invoice->company?->headQuarters?->zip,
                        'notes' => $invoice->company?->notes,
                        'status' => $invoice->company?->status,
                        'expiryDate' => $invoice->company?->expiry_dt ? Carbon::parse($invoice->company?->expiry_dt)->toDateString() : '',
                        'termsOfPayment' => $invoice->company?->terms_of_payment,
                    ] : null,
                    'performanceRecordDetails' => $invoice->performanceRecord ? [
                        'id' => $invoice->performanceRecord->id,
                        'company' => $invoice->performanceRecord->customer?->company_name,
                        'companyId' => $invoice->performanceRecord->customer?->id,
                        'advisorId' => $invoice->performanceRecord->advisor_id,
                        'companyNumber' => $invoice->performanceRecord->customer?->company_number,
                        'defaultServiceProduct' => $invoice->performanceRecord->customer?->default_service_product ?? null,
                        'defaultServiceHourlyRate' => $invoice->performanceRecord->customer?->default_service_hourly_rate ?? null,
                        'defaultServiceDailyRate' => $invoice->performanceRecord->customer?->default_service_daily_rate ?? null,
                        'invoice' => $invoice->performanceRecord?->invoice,
                        'totalHours' => $invoice->performanceRecord->total_hours,
                        'goodWillHours' => $invoice->performanceRecord->good_will_hours,
                        'startDate' => $invoice->performanceRecord->start_date,
                        'endDate' => $invoice->performanceRecord->end_date,
                        'createdDate' => Carbon::parse($invoice->performanceRecord->created_at)->format('Y-m-d'),
                        'performanceNumber' => $invoice->performanceRecord->performance_number,
                        'status' => $invoice->performanceRecord->status,
                        'updatedAt' =>  Carbon::parse($invoice->performanceRecord->updated_at)->toDateString(),
                        'editedUserId' =>  $invoice->performanceRecord->edited_user_id,
                    ] : null
                ];
            });
        return response()->json(['invoices' => $invoice, 'invoiceTypes' => InvoiceType::ALL], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * updated the open post status
     * @param {$request} Request
     * @param {$id} id of the invoice to be updated
     */
    public function updateOpenPosts(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->reminder_sent = true;
        $now = Carbon::now();
        $invoice->reminder_stop_until = $now->addDays(7);
        $invoice->save();
        return response()->json([
            "success" => true,
            "message" => "Reminder has been set to true"
        ]);
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
            'levelId' => 'required',
            'reminderStopUntil' => 'nullable|date',
            'reminderStop' => 'nullable|boolean'
        ]);
        $invoice = Invoice::findOrFail($id);
        $invoice->reminder_level_id = $request->levelId;
        $date = null;
        if ($invoice->isDirty('reminder_level_id')) {
            $date = Carbon::now()->addDays(7);
        }
        $invoice->reminder_stop_until = $request->reminderStopUntil ? Carbon::parse($request->reminderStopUntil) : $date;
        $invoice->reminder_stop = $request->reminderStop ?? 0;
        $invoice->save();
        return response()->json(['message' => 'Invoice status is saved']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
