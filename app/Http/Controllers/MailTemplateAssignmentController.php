<?php

namespace App\Http\Controllers;

use App\Models\MailTemplateAssignment;
use App\Services\MailTemplateAssignmentService;
use App\Http\Requests\MailTemplateAssignmentRequest;
use App\Http\Resources\MailTemplateAssignmentResource;
use Illuminate\Http\Request;

class MailTemplateAssignmentController extends Controller
{
    public $mailTemplateAssignmentService;

    public function __construct(MailTemplateAssignmentService $mailTemplateAssignmentService)
    {
        $this->mailTemplateAssignmentService = $mailTemplateAssignmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MailTemplateAssignmentResource::collection(MailTemplateAssignment::all());
    }

    /**
     * saves the mail template assignment 
     * @param MailTemplateAssignmentRequest $request
     */
    public function saveMailTemplateAssignment(MailTemplateAssignmentRequest $request)
    {
        $validated_data = $request->validated();
        $this->mailTemplateAssignmentService->saveMailTemplateAssignment($validated_data);
        return response()->json([
            "success" => true,
            "message" => "Record saved succcessfully"
        ]);
    }

    /**
     * filters and returns the mail template resource based on module
     * @param Request $request
     * @return JSONResponse 
     */
    public function mailTemplateByModule(Request $request)
    {
        $mail_template_assignment = MailTemplateAssignment::where("module", $request->module)->first();
        return $mail_template_assignment ? new MailTemplateAssignmentResource($mail_template_assignment) : null;
    }
}
