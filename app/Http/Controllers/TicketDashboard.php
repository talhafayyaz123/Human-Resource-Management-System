<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\CustomHelper;

class TicketDashboard extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    use CustomHelper;

    public function __invoke(Request $request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');

        $group_sort_by = $request->get('groupSortBy');
        $group_sort_order = $request->get('groupSortOrder');
        $open_and_new_tickets = Ticket::where('state', '!=', 'resolved');
        $open_and_new_tickets_new = clone $open_and_new_tickets;
        if ($group_sort_by && $group_sort_order) {
            $open_and_new_tickets_new = $this->applySortingBeforePagination($open_and_new_tickets_new, $group_sort_by, $group_sort_order);
        }
        $open_and_new_tickets_all = $open_and_new_tickets_new->get();

        $group_by = $request->groupBy ?? Str::snake('companyId');

        $open_and_new_tickets_groupded_collection = $open_and_new_tickets_all->map(function ($item) {
            return [
                'id' => $item->id,
                'companyId' => $item->company_id ?? '',
                'status' => $item->state,
                'ticketNumber' => $item->ticket_number,
                'companyName' => $item->company?->company_name ?? '',
                'title' => $item->title,
                'assigneeId' => $item->assignee,
                'priority' => $item->priority,
                'updatedAt' => Carbon::parse($item->updated_at)->toDateString(),
                'createdAt' => Carbon::parse($item->created_at)->toDateString(),
                'userId' => $item->user_id
            ];
        })->groupBy($group_by);

        $total_open_new_tickets = $open_and_new_tickets->count();

        $total_incident_ticket = Ticket::whereIn('state', ['open', 'new'])->where('type', 'incident')->count();

        $total_new_tickets = Ticket::where('state', 'new')->count();

        $last_updated_records = Ticket::whereBetween('updated_at', [now()->subMinutes(15), now()])->count();

        $assignee_group_ticket_count = $open_and_new_tickets_all->groupBy(function ($item, $key) {
            return $item['assignee'] ?? '';
        })->map->count();

        $tickets_in_the_last_week = Ticket::whereBetween(
            'created_at',
            [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]

        )->get();
        $status_ticket_count = $tickets_in_the_last_week->groupBy(function ($item, $key) {
            return $item['state'] ?? '';
        })->map->count();

        if ($sort_by && $sort_order) {
            $open_and_new_tickets = $this->applySortingBeforePagination($open_and_new_tickets, $sort_by, $sort_order);
        }
        $open_and_new_tickets_collection = $open_and_new_tickets->when($request->assignee, function ($query) use ($request) {
            $query->where('assignee', $request->assignee);
        })->paginate(10)->through(function ($item) {
            return [
                'id' => $item->id,
                'companyId' => $item->company_id ?? '',
                'status' => $item->state,
                'ticketNumber' => $item->ticket_number,
                'companyName' => $item->company?->company_name ?? '',
                'title' => $item->title,
                'assigneeId' => $item->assignee,
                'priority' => $item->priority,
                'updatedAt' => Carbon::parse($item->updated_at)->toDateString(),
                'createdAt' => Carbon::parse($item->created_at)->toDateString(),
                'userId' => $item->user_id
            ];
        });
        return response()->json(
            [
                'openAndNewTickets' => $open_and_new_tickets_collection,
                'openAndNewTicketsGrouped' => $open_and_new_tickets_groupded_collection,
                'openAndNewTicketsCount' => $total_open_new_tickets,
                'incidentTicketsCount' => $total_incident_ticket,
                'newTicketsTotal' => $total_new_tickets,
                'lastUpdatedRecords' => $last_updated_records,
                'assigneeGroupTicketCount' => $assignee_group_ticket_count,
                'statusTicketCount' => $status_ticket_count
            ]
        );
    }
}
