<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Services\AMSService;
use App\Http\Requests\ApplicationManagementServiceRequest;
use App\Http\Resources\ApplicationManagementServiceResource;
use App\Http\Resources\ApplicationManagementServiceDashboardResource;
use App\Models\ApplicationManagementService;
use App\Models\OutboundedContract;
use App\Http\Resources\OutboundedContractResource;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class ApplicationManagementServiceController extends Controller
{
    use CustomHelper;

    public AMSService $amsService;

    public function __construct(AMSService $amsService)
    {
        $this->middleware('check.permission:application-management-services.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:application-management-services.create', ['only' => ['store']]);
        $this->middleware('check.permission:application-management-services.edit', ['only' => ['update']]);
        $this->middleware('check.permission:application-management-services.delete', ['only' => ['destroy']]);
        $this->amsService = $amsService;
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
        $model = new ApplicationManagementService();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        // Paginate the ProjectProtocol records
        $ams = $model->paginate($per_page);
        // Return the paginated ProjectProtocolResource
        return ApplicationManagementServiceResource::collection($ams);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApplicationManagementServiceRequest $request)
    {
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $ams = $this->amsService->createAMS($data);
        $content = [
            'moduleAction' => 'createAMS',
            'data' => $ams->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $ams);
        return response()->json([
            'success' => true,
            'message' => 'Record created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $ams = ApplicationManagementService::findOrFail($id);
        $request['show'] = true;
        return new ApplicationManagementServiceResource($ams);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ApplicationManagementServiceRequest $request, $id)
    {
        $ams = ApplicationManagementService::findOrFail($id);
        $validated_data = $request->validated();
        $data = $this->convertKeysToSnakeCase(collect($validated_data));
        $ams = $this->amsService->updateAMS($ams, $data);
        $content = [
            'moduleAction' => 'updateAMS',
            'data' => $ams->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content, $ams);
        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully.'
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
        $ams = ApplicationManagementService::findOrFail($id);
        $this->amsService->deleteAMS($ams);
        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully.'
        ]);
    }

    /**
     * Get the specified resource based on customer_id
     *
     * @param  int  $customerId
     * @return \Illuminate\Http\Response
     */
    public function getByCustomerId($customerId)
    {
        $ams = ApplicationManagementService::where('customer_id', $customerId)->get();
        return ApplicationManagementServiceResource::collection($ams);
    }

    /**
     * get ams filtered by customer and attachment not null
     * @param string $id
     * @return JSONResponse
     */
    public function getByCustomerAndAttachment($id)
    {
        $ams = ApplicationManagementService::where('customer_id', $id)->get();
        return ApplicationManagementServiceResource::collection($ams);
    }

    public function showRevamp($id)
    {
        $ams = ApplicationManagementService::findOrFail($id);
        $related_software = $ams->software;
        $related_software_id = $related_software?->id;
        $outbounded_contracts = collect();
        if (!empty($related_software_id)) {
            $outbounded_contracts = OutboundedContract::whereHas('attachments', function ($query) use ($related_software_id) {
                $query->where('software_id', $related_software_id);
            })->where('receiver_id', $ams->customer_id)->get();
        }

        $tickets = Ticket::filter(staticRequest::only('search', 'state', 'priority', 'assigneeId', 'isArchived'))
            ->whereNot('state', 'new')
            ->where('ams_id', $ams->id)
            ->withSum('comments', 'time')
            ->get();

        $total_accounted_time_sum = $tickets->sum('comments_sum_time');

        $ams_data = [
            'id' => $ams->id,
            'amsNumber' => $ams->ams_number,
            'software' => $related_software,
            'customer' => $ams->customer ? [
                'id' => $ams->customer->id,
                'companyName' => $ams->customer->company_name,
                'vatId' => $ams->customer->vat_id,
                'url' => $ams->customer->url,
                'type' => $ams->customer->type,
                'customerType' => $ams->customer->customer_type,
                'companyNumber' => $ams->customer->company_number,
                'state' => '',
                'city' => '',
                'country' => '',
                'address' => '',
                'notes' => $ams->customer->notes,
                'status' => $ams->customer->status,
                'expiryDate' => $ams->customer->expiry_dt ? Carbon::parse($ams->customer->expiry_dt)->toDateString() : '',
                'termsOfPayment' => $ams->customer->terms_of_payment,
                'contactReportSources' => $ams->customer?->contactReportSources
            ] : null,
            'serviceHoursYear' => $ams->service_hours_year,
            'remainingHours' => $ams->remaining_hours - $total_accounted_time_sum,
            'hourlyRate' => $ams->hourly_rate,
            'monthlyAmount' => $ams->monthly_amount,
            'outboundedContracts' => OutboundedContractResource::collection($outbounded_contracts)
        ];
        return response()->json(['data' => $ams_data]);
    }

    public function getAmsTickets($id, Request $request)
    {
        $ams = ApplicationManagementService::findOrFail($id);

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $tickets = Ticket::filter(staticRequest::only('search', 'state', 'priority', 'assigneeId', 'isArchived'))
            ->whereNot('state', 'new')
            ->where('ams_id', $ams->id)
            ->get();
        $tickets = $tickets->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'type' => $item->type,
                'ticketNumber' => $item->ticket_number,
                'state' => $item->state,
                'priority' => $item->priority,
                'userId' => $item->user_id,
                'assignee' => $item->assignee,
                // 'userType' => Auth::user()->type,
                'contactType' => $item->contact_type,
                'companyId' => $item->company_id,
                'createdAt' => $item->created_at,
                'isArchived' => $item->is_archived,
                'visibility' => $item->visibility ?? '',
                'totalAccountedTime' => TicketComment::where('ticket_id', $item->id)->sum('time'),
            ];
        });
        $total_accounted_time_sum = $tickets->sum('totalAccountedTime');
        if ($sort_by && $sort_order) {
            $tickets = $this->applySorting($tickets, $sort_by, $sort_order);
        }
        return response()->json([
            'id' => $ams->id,
            'amsNumber' => $ams->ams_number,
            'customerId' => $ams->customer ? [
                'id' => $ams->customer->id,
                'companyName' => $ams->customer->company_name,
                'vatId' => $ams->customer->vat_id,
                'url' => $ams->customer->url,
                'type' => $ams->customer->type,
            ] : '',
            'remainingHours' => $ams->remaining_hours - $total_accounted_time_sum,
            'hourlyRate' => $ams->hourly_rate,
            'monthlyAmount' => $ams->monthly_amount,
            'tickets' => $tickets,
            'serviceTime' => $ams->service_hours_year
        ]);
    }

    public function getAmsSupportDashboard(Request $request)
    {
        $per_page = $request->perPage ?? 25;

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new ApplicationManagementService();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        // Paginate the records
        $ams = $model->paginate($per_page);
        // Return the paginated ProjectProtocolResource
        return ApplicationManagementServiceDashboardResource::collection($ams);
    }
}
