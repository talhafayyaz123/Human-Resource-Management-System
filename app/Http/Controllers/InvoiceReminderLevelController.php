<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Models\GlobalSetting;
use App\Models\Invoice;
use App\Models\InvoiceReminderLevel;
use App\Traits\CustomHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InvoiceReminderLevelController extends Controller
{
    use CustomHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new InvoiceReminderLevel();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $invoice_reminders = $model->when(isset($request->search), function ($query)  use ($request) {
            $query->where('level_name', 'LIKE', '%' . $request->search . '%');
        })->paginate(25);
        $invoice_collect = $invoice_reminders->map(function ($reminder) {
            return [
                'id' => $reminder->id,
                'levelName' => $reminder->level_name,
                'periodDays' => $reminder->period_days,
                'reminderFee' => $reminder->reminder_fee
            ];
        });

        return response()->json([
            'data' => $invoice_collect,
            'invoiceReminders' => $invoice_reminders
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
        $validated_data =  $request->validate([
            'levelName' => 'required|string|in:' . implode(',', InvoiceStatus::WARNING_LEVEL_STATUS),
            'periodDays' => 'required|integer',
            'reminderFee' => 'required|numeric',
            'startText' => 'required|string',
            'endText' => 'required|string',
            'mailText' => 'required|string',
            'name' => 'required|string',
            'documentTemplateId' => 'required|string'
        ]);
        $model = InvoiceReminderLevel::where("level_name", $request->levelName)->first();
        if (!empty($model)) {
            throw new Exception("A invoice reminder with the same name already exists");
        }
        $model = new InvoiceReminderLevel();
        $this->saveData($model, $validated_data);
        return response()->json(['message' => 'Data saved successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice_reminder_level = InvoiceReminderLevel::findOrFail($id);
        return response()->json([
            'data' =>
            [
                'levelName' => $invoice_reminder_level->level_name,
                'periodDays' => $invoice_reminder_level->period_days,
                'reminderFee' => $invoice_reminder_level->reminder_fee,
                'startText' => $invoice_reminder_level->start_text,
                'endText' => $invoice_reminder_level->end_text,
                'mailText' => $invoice_reminder_level->mail_text,
                'name' => $invoice_reminder_level->name,
                'documentTemplateId' => $invoice_reminder_level->document_template_id
            ]
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
            'levelName' => 'required|string|in:' . implode(',', InvoiceStatus::WARNING_LEVEL_STATUS),
            'periodDays' => 'required|integer',
            'reminderFee' => 'required|numeric',
            'startText' => 'required|string',
            'endText' => 'required|string',
            'mailText' => 'required|string',
            'name' => 'required|string',
            'documentTemplateId' => 'required|string'
        ]);
        $invoice_reminder_level = InvoiceReminderLevel::firstOrNew(['level_name' => $request->levelName]);
        $this->saveData($invoice_reminder_level, $request);
        return response()->json(['message' => 'Data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $invoice_reminder_level = InvoiceReminderLevel::findOrFail($id);
            $global_setting = GlobalSetting::where('key', $invoice_reminder_level->level_name)->first();
            if (isset($global_setting)) {
                $global_setting->delete();
            }
            $invoice_reminder_level->delete();
        });

        return response()->json(['message' => 'Data deleted successfully']);
    }


    public function saveData($model, $data)
    {
        $data = $this->convertKeysToSnakeCase(collect($data));
        $model->fill($data);
        $model->save();
        return $model;
    }

    /**
     * Display the specified resource by filtering through level name.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByLevelName(Request $request)
    {
        $level_name = $request->get('levelName');
        $invoice_reminder_level = InvoiceReminderLevel::where('level_name', 'LIKE', $level_name)->first();
        if (!$invoice_reminder_level) {
            return response()->json([
                "success" => false,
                "message" => "No Invoice reminder level exists for this level"
            ], 404);
        }
        return response()->json([
            'data' =>
            [
                'levelName' => $invoice_reminder_level->level_name,
                'periodDays' => $invoice_reminder_level->period_days,
                'reminderFee' => $invoice_reminder_level->reminder_fee,
                'startText' => $invoice_reminder_level->start_text,
                'endText' => $invoice_reminder_level->end_text,
                'mailText' => $invoice_reminder_level->mail_text,
                'name' => $invoice_reminder_level->name,
                'documentTemplateId' => $invoice_reminder_level->document_template_id
            ]
        ]);
    }
}
