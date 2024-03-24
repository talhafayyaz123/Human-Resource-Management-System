<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PersonalRequestService;
use App\Http\Requests\PersonalRequestRequest;
use App\Http\Resources\PersonalRequestResource;
use App\Models\PersonalRequest;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\DB;

class PersonalRequestController extends Controller
{

    use CustomHelper;

    protected $personalRequestService;

    public function __construct(PersonalRequestService $personalRequestService)
    {
        $this->middleware('check.permission:personal-requests.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:personal-requests.create', ['only' => ['store']]);
        $this->middleware('check.permission:personal-requests.edit', ['only' => ['update']]);
        $this->middleware('check.permission:personal-requests.delete', ['only' => ['destroy']]);
        $this->personalRequestService = $personalRequestService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 10;
        $personal_requests = PersonalRequest::search($request->search)->paginate($per_page);
        return response()->json([
            'data' => PersonalRequestResource::collection($personal_requests),
            'links' => $personal_requests->links(),
            'current_page' => $personal_requests->currentPage(),
            'from' => $personal_requests->firstItem(),
            'last_page' => $personal_requests->lastPage(),
            'path' => request()->url(),
            'per_page' => $personal_requests->perPage(),
            'to' => $personal_requests->lastItem(),
            'total' => $personal_requests->total(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonalRequestRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        try {
            DB::transaction(function () use ($data) {
                $this->personalRequestService->createPersonalRequest($data);
            });
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ], 500);
        }
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Perosnal Request'])], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $personal_request = PersonalRequest::findOrFail($id);
        return new PersonalRequestResource($personal_request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PersonalRequestRequest $request, $id)
    {
        $validated_data = $request->validated();
        $personal_request = PersonalRequest::findOrFail($id);
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        try {
            DB::transaction(function () use ($data, $personal_request) {
                $this->personalRequestService->updatePersonalRequest($personal_request,  $data);
            });
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ], 500);
        }
        return response()->json(['message' => trans('messages.record_updated_success', ['name' => 'Personal Request'])], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $personal_request = PersonalRequest::findOrFail($id);
        $this->personalRequestService->deletePersonalRequest($personal_request);
        return response()->json(['message' => trans('messages.record_deleted_success', ['name' => 'Personal Request'])], 200);
    }
}
