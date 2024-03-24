<?php

namespace App\Http\Controllers;

use App\Models\FormOfContract;
use App\Http\Requests\FormOfContractRequest;
use App\Http\Resources\FormOfContractCollection;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;


class FormOfContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     use CustomHelper;
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new FormOfContract;
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        
        return new FormOfContractCollection($model->orderBy('created_at')->paginate($per_page));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormOfContractRequest $request)
    {
        $validated_data = $request->validated();
        $form_of_contract = new FormOfContract();
        $form_of_contract->name = $validated_data["name"];
        $form_of_contract->save();

        return response()->json([
            "success" => true,
            "message" => "Record Created sucessfully",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $form_of_contract = FormOfContract::findOrFail($id);
        return [
            "id" => $form_of_contract->id,
            "name" => $form_of_contract->name
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FormOfContractRequest $request, $id)
    {
        $form_of_contract = FormOfContract::findOrFail($id);

        $validated_data = $request->validated();
        $form_of_contract->name = $validated_data["name"];
        $form_of_contract->save();

        return response()->json([
            "success" => true,
            "message" => "Record Updated sucessfully",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $form_of_contract = FormOfContract::findOrFail($id);
        $form_of_contract->delete();

        return response()->json([
            "success" => true,
            "message" => "Record deleted successfully"
        ]);
    }
}
