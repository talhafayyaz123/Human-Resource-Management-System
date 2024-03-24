<?php

namespace App\Http\Controllers;

use App\Helpers\TicketHelper;
use Illuminate\Http\Request;

class PartnerTicketController extends Controller
{
    protected $ticketHelper;

    /**
     * Runs when instance is initiated
     */
    public function __construct(TicketHelper $ticketHelper)
    {
        $this->middleware('check.permission:partner-ticket.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:partner-ticket.create', ['only' => ['store']]);
        $this->middleware('check.permission:partner-ticket.edit', ['only' => ['update']]);
        $this->middleware('check.permission:partner-ticket.delete', ['only' => ['destroy']]);
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
        return $this->ticketHelper->index($request, 'partner');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->ticketHelper->store($request, 'partner');
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->ticketHelper->destroy($id);
    }


}
