<?php

namespace App\Http\Controllers;

use App\Helpers\TicketHelper;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Traits\CustomHelper;
use App\Traits\SetGlobalNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\GetUserProfilePicture;

class TicketController extends Controller
{
    use CustomHelper;
    use SetGlobalNumber;
    use GetUserProfilePicture;

    protected $ticketHelper;
    /**
     * Runs when instance is initiated
     */
    public function __construct(TicketHelper $ticketHelper)
    {
        $this->middleware('check.permission:ticket.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:ticket.create', ['only' => ['store']]);
        $this->middleware('check.permission:ticket.edit', ['only' => ['update']]);
        $this->middleware('check.permission:ticket.delete', ['only' => ['destroy']]);
        $this->ticketHelper = $ticketHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->ticketHelper->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->ticketHelper->store($request);

    }



    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showByCompany(Request $request)
    {

        $model = new Ticket;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $models = $model->where('company_id', $request->id);
        if (!$this->hasRole($request, 'admin')) {
            $models = $models->where('is_archived', 0);
        }
        $models = $models->get();
        $model_collect = $models->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'ticketNumber' => $item->ticket_number,
                'state' => $item->state,
                'priority' => $item->priority,
                'userId' => $item->user_id,
                'assignee' => $item->assignee,
                'contactType' => $item->contact_type,
                'companyId' => $item->company_id,
                'createdAt' => $item->created_at,
                'isArchived' => $item->is_archived,
                'totalAccountedTime' => TicketComment::where('ticket_id', $item->id)->sum('time')
            ];
        });
        return response()->json([
            'data' => $model_collect
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->ticketHelper->show($id);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->ticketHelper->update($request, $id);
    }



    /**
     * gets the count of open tickets
     * @return JSONResponse
     */
    public function openTicketCount()
    {
        $tickets = Ticket::where('state', 'new');
        return response()->json([
            'count' => $tickets->where('customer_type', 'customer')->count(),
            'partnerCount' => $tickets->where('customer_type', 'partner')->count()
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->ticketHelper->destroy($id);
    }

    /**
     * Restore the previously deleted source.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $model = Ticket::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }

    /**
     * show comments of particular ticket
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function showTicketComments(Request $request)
    {
        $request->validate([
            'objectId' => 'required',
            'userId' => 'required'
        ]);
        $model = TicketComment::where('ticket_id', $request->objectId)
            ->where('is_archived', false)
            ->where('user_id', $request->userId)
            ->where('is_added', false)
            ->whereNotNull('time');

        if ($request->date) {
            $model->whereDate('created_at', '=', Carbon::parse($request->date));
        }
        $data = $model->paginate(10)->through(function ($item) {
            return [
                'id' => $item->id,
                'time' => $item->time,
                'text' => $item->text,
                'userId' => $item->user_id,
                'createdAt' => $item->created_at,
            ];
        });
        return response()->json($data);
    }

    /**
     * Get ticket data by its comment id
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getTicketByComment(Request $request, $id)
    {
        $model = Ticket::whereHas('comments', function ($query) use ($id) {
            $query->where('id', $id);
        })->first();

        return response()->json([
            'ticket' => [
                'id' => $model->id,
                'title' => $model->title,
                'ticketNumber' => $model->ticket_number,
                'state' => $model->state,
                'priority' => $model->priority,
                'contactType' => $model->contact_type,
                'userId' => $model->user_id,
                'isArchived' => $model->is_archived,
                'assignee' => $model->assignee,
                'companyId' => $model->company_id,
                'createdAt' => $model->created_at,
                'visibility' => $model->visibility ?? '',
            ]
        ]);
    }

    public function reOpenTicket($id, $status)
    {
        $ticket = Ticket::find($id);
        if ($ticket) {
            $ticket->state = $status;
            $ticket->save();
            return response()->json(['message' => 'Ticket status updated successfully']);
        }
        return response()->json(['message' => 'Invalid ticket id'], 400);
    }
}
