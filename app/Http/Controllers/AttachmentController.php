<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachmentRequest;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;

use App\Services\AttachmentService;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;


class AttachmentController extends Controller
{
    use CustomHelper;
    public AttachmentService $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->middleware('check.permission:attachments.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:attachments.create', ['only' => ['store']]);
        $this->middleware('check.permission:attachments.edit', ['only' => ['update']]);
        $this->middleware('check.permission:attachments.delete', ['only' => ['destroy']]);
        $this->attachmentService = $attachmentService;
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
        $model = new Attachment();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        if ($request->has('contractTypeId')) {
            $model = $model->where('contract_type_id', $request->contractTypeId);
        }
        // Paginate the ContractType records
        $attachments = $model->filter(staticRequest::only('search'))->paginate($per_page);
        // Return the paginated FleetCarResource
        return response()->json([
            'data' => AttachmentResource::collection($attachments),
            'links' => $attachments->links(),
            'current_page' => $attachments->currentPage(),
            'from' => $attachments->firstItem(),
            'last_page' => $attachments->lastPage(),
            'path' => request()->url(),
            'per_page' => $attachments->perPage(),
            'to' => $attachments->lastItem(),
            'total' => $attachments->total(),
        ]);
    }
    /**
     * Stores the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(AttachmentRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $attachment = $this->attachmentService->createAttachment($data);
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Attachment']), 'id' => $attachment->id], 200);
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
        $attachment = Attachment::findOrFail($id);

        // Return a single FleetCarResource for the found record
        return new AttachmentResource($attachment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(AttachmentRequest $request, $id)
    {
        $validated_data = $request->validated();
        $attachment = Attachment::findOrFail($id);
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $attachment = $this->attachmentService->updateAttachment($attachment,  $data);
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Attachment']), 'id' => $attachment->id], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attachment = Attachment::findOrFail($id);
        $this->attachmentService->deleteAttachment($attachment);
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Attachment'])], 200);
    }
}
