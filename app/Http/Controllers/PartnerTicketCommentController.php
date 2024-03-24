<?php

namespace App\Http\Controllers;

use App\Helpers\TicketHelper;
use Illuminate\Http\Request;

class PartnerTicketCommentController extends Controller
{
    protected $ticketHelper;
    /**
     * Runs when instance is initiated
     */
    public function __construct(TicketHelper $ticketHelper)
    {
        $this->middleware('check.permission:partner-ticket-comment.create', ['only' => ['store']]);
        $this->middleware('check.permission:partner-ticket-comment.edit', ['only' => ['update']]);
        $this->middleware('check.permission:partner-ticket-comment.delete', ['only' => ['destroy']]);
        $this->ticketHelper = $ticketHelper;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->ticketHelper->commentStore($request);
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
        return $this->ticketHelper->commentUpdate($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->ticketHelper->commentDestroy($id);
    }
}
