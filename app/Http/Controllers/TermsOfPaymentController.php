<?php

namespace App\Http\Controllers;

use App\Models\TermsOfPayment;
use App\Utils\Logger;
use Illuminate\Http\Request;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\DB;
use App\Traits\CustomHelper;

class TermsOfPaymentController extends Controller
{
    /**
     * Run on instantiate
     */
    use CustomHelper;

    public function __construct()
    {
        $this->middleware('check.permission:terms-of-payment.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:terms-of-payment.create', ['only' => ['store']]);
        $this->middleware('check.permission:terms-of-payment.edit', ['only' => ['update']]);
        $this->middleware('check.permission:terms-of-payment.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new TermsOfPayment;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->orderBy('created_at')
            // ->filter($request->only('search', 'status'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(function ($item) {
                   return [
                    'id' => $item->id,
                    "noOfDays1" => $item->no_of_days_1,
                    "percentage1" => $item->percentage_1,
                    "noOfDays2" => $item->no_of_days_2,
                    "percentage2" => $item->percentage_2,
                    "percentage3" => $item->percentage_3,
                    "percentage3" => $item->percentage_3,
                    "name" => $item->name,
                    "offerText" => $item->offer_text,
                    "orderText" => $item->order_text,
                    "invoiceText" => $item->invoice_text,
                    "paymentTerms" => $item->payment_terms,
                ];
            });

        return response()->json($model);
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
            "percentage1" => "required",
            "noOfDays1" => "required",
            "name" => "required"
        ]);

        //Create Terms Of Payment
        $model = new TermsOfPayment;
        $this->saveData($model, $request);

        return response()->json(['message' => 'Record created successfully.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = TermsOfPayment::findOrFail($id);
        return response()->json(["data" => [
            'id' => $model->id,
            "percentage1" => $model->percentage_1,
            "noOfDays1" => $model->no_of_days_1,
            "percentage2" => $model->percentage_1,
            "noOfDays2" => $model->no_of_days_2,
            "percentage2" => $model->percentage_2,
            "noOfDays3" => $model->no_of_days_3,
            "percentage3" => $model->percentage_3,
            "name" => $model->name,
            "offerText" => $model->offer_text,
            "orderText" => $model->order_text,
            "invoiceText" => $model->invoice_text,
            "paymentTerms" => $model->payment_terms,
        ]]);
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
            "percentage1" => "required",
            "noOfDays1" => "required",
            "name" => "required"
        ]);

        //Create Terms Of Payment
        $model = TermsOfPayment::where("id", $id)->first();
        $this->saveData($model, $request);

        return response()->json(['message' => 'Record updated successfully.'], 200);
    }


    /**
     * Saves the data based on provided resource item
     *
     * @param   object  $model
     * @param   object  $request
     * @param   array   Response
     */
    public function saveData($model, $request)
    {
        $global_survey_setting = GlobalSetting::where('key', 'terms_of_payment')->first();
        if (empty($global_survey_setting)) {
            $global_setting = new GlobalSetting;
            $global_setting->key = 'terms_of_payment';
            $global_setting->value = 1;
            $global_setting->save();
        } else {
            $global_setting = tap(DB::table('global_settings')->where('key', 'terms_of_payment'))->update([
                'value' => DB::raw("value+1")
            ])->first();
        }
        $addZero = ((int)$global_setting->value) < 10 ? '0' : '';
        $model->payment_terms = 'ZB' . $addZero . $global_setting->value;
        $model->percentage_1 = $request->percentage1;
        $model->no_of_days_1 = $request->noOfDays1;
        $model->percentage_2 = $request->percentage2;
        $model->no_of_days_2 = $request->noOfDays2;
        $model->percentage_3 = $request->percentage3;
        $model->no_of_days_3 = $request->noOfDays3;
        $model->offer_text = $request->offerText;
        $model->order_text = $request->orderText;
        $model->invoice_text = $request->invoiceText;
        $model->name = $request->name;
        $model->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TermsOfPayment::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }

    /**
     * Restore the previously deleted source.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $model = TermsOfPayment::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }
}