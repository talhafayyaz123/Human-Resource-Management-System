<?php

namespace App\Http\Controllers;

use App\Models\TravelExpenseInvoiceType as InvoiceType;
use App\Utils\Logger;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;

class TravelExpenseInvoiceTypeController extends Controller
{
    use CustomHelper;

    /**
     *Runs when instance is initiated
     */

    public function __construct()
    {
        $this->middleware('check.permission:travel-expense-invoice-type.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:travel-expense-invoice-type.create', ['only' => ['store']]);
        $this->middleware('check.permission:travel-expense-invoice-type.edit', ['only' => ['update']]);
        $this->middleware('check.permission:travel-expense-invoice-type.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $per_page = $request->perPage;

        $model = new InvoiceType;
        $models = $model->when($request->search, function ($query) use ($request) {
            $query->where("name", "LIKE", "%" . $request->search . "%");
        })->paginate($per_page);

        $model_collect = $models->map(function ($item) {
            return [
                'id' => $item->id,
                "name" => $item->name,
            ];
        });
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model_collect = $this->applySorting($model_collect, $sort_by, $sort_order);
        }
        return response()->json([
            'data' => $model_collect,
            'links' => $models->links(),
            'meta' => [
                'current_page' => $models->currentPage(),
                'from' => $models->firstItem(),
                'last_page' => $models->lastPage(),
                'path' => request()->url(),
                'per_page' => $models->perPage(),
                'to' => $models->lastItem(),
                'total' => $models->total(),
            ]
        ], 200);
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
            "name" => "required",
        ]);

        //Create Invoice Type
        $model = new InvoiceType;
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
        $model = InvoiceType::find($id);
        return response()->json(["data" => [
            'id' => $model->id,
            "name" => $model->name,
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
        ]);

        //Create Invoice Type
        $model = InvoiceType::find($id);
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
        InvoiceType::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }
}
