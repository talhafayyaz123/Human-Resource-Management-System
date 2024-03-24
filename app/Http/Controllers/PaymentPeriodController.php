<?php

namespace App\Http\Controllers;

use App\Models\PaymentPeriod;
use App\Utils\Logger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Request as staticRequest;

class PaymentPeriodController extends Controller
{

    /**
     * Runs when instance is initiated
     */
    use CustomHelper;
    public function __construct()
    {
        $this->middleware('check.permission:product-period.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:product-period.create', ['only' => ['store']]);
        $this->middleware('check.permission:product-period.edit', ['only' => ['update']]);
        $this->middleware('check.permission:product-period.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $payment_periods = new PaymentPeriod();
        if ($sort_by && $sort_order) {
            $payment_periods = $this->applySortingBeforePagination($payment_periods, $sort_by, $sort_order);
        }
        $payment_periods = $payment_periods->orderBy('created_at')
            ->filter(staticRequest::only('search'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(fn ($period) => [
                'id' => $period->id,
                'name' => $period->name,
                'billingCycle' => $period->billing_cycle,
                'isDefault' => $period->is_default,
                'createdAt' => Carbon::parse($period->created_at)->format('d/m/y'),
            ]);
        return response()->json(['periods' => $payment_periods]);
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
            "name" => "required|string",
            "billingCycle" => "required",
            'isDefault' => 'nullable|boolean'
        ]);
        if (PaymentPeriod::where('is_default', true)->exists()) {
            return response()->json(['message' => 'A payment period with  product price already exists'], 422);
        }
        //Create Product period
        $model = new PaymentPeriod;
        $model->name = $request->name;
        $model->billing_cycle = $request->billingCycle;
        $model->is_default = $request->isDefault ?? null;
        $model->save();
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Product period'])], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $period = PaymentPeriod::where("id", $id)->first();
        return response()->json(['period' => [
            'id' => $period->id,
            "name" => $period->name,
            "billingCycle" => $period->billing_cycle,
            "isDefault" => $period->is_default ? true : false
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
            "name" => "required",
            "billingCycle" => "required",
            'isDefault' => 'nullable|boolean',
        ]);
        if (PaymentPeriod::where('is_default', true)->whereNotIn('id', [$id])->exists() && $request->isDefault) {
            return response()->json(['message' => 'A payment period with default price already exists'], 422);
        }
        $model = PaymentPeriod::where(["id" => $id])->first();
        $model->name = $request->name;
        $model->billing_cycle = $request->billingCycle;
        $model->is_default = $request->isDefault ?? null;
        $model->save();
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PaymentPeriod::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }
}
