<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractTypeRequest;
use App\Http\Resources\ContractTypeResource;
use App\Http\Resources\AttachmentResource;
use App\Models\ContractType;

use App\Services\ContractTypeService;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;


class ContractTypeController extends Controller
{
    use CustomHelper;
    public ContractTypeService $contractTypeService;

    public function __construct(ContractTypeService $contractTypeService)
    {
        $this->middleware('check.permission:contract-types.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:contract-types.create', ['only' => ['store']]);
        $this->middleware('check.permission:contract-types.edit', ['only' => ['update']]);
        $this->middleware('check.permission:contract-types.delete', ['only' => ['destroy']]);
        $this->contractTypeService = $contractTypeService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new ContractType();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->orderBy('created_at')
            // ->filter($request->only('search', 'status'))
            ->paginate($per_page)
            ->withQueryString()
            ->through(function ($item) {
                return new ContractTypeResource($item);
            });

        return response()->json($model);
        
    }
    /**
     * Stores the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(ContractTypeRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $contract_type = $this->contractTypeService->createContractType($data);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Contract Type']), 'id' => $contract_type->id], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find the FleetCar record by ID
        $contract_type = ContractType::findOrFail($id);

        // Return a single FleetCarResource for the found record
        return new ContractTypeResource($contract_type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(ContractTypeRequest $request, $id)
    {
        $validated_data = $request->validated();
        $contract_type = ContractType::findOrFail($id);
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $contract_type = $this->contractTypeService->updateContractType($contract_type,  $data);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Contract Type']), 'id' => $contract_type->id], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contract_type = ContractType::findOrFail($id);
        $this->contractTypeService->deleteContractType($contract_type);
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Contract Type'])], 200);
    }

    /**
     * Get the attachments listing filtered by contract type
     * @param integer $id
     * @return AttachmentResource collection
     */
    public function attachmentsByContractType($id)
    {
        $contract_type = ContractType::findOrFail($id);
        return AttachmentResource::collection($contract_type?->attachments ?? []);
    }
}
