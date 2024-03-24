<?php

namespace App\Http\Controllers;

use App\Helpers\TicketHelper;
use App\Models\GlobalNotificationList;
use App\Helpers\GlobalSettingHelper;
use App\Models\TicketComment as Comment;
use App\Models\UploadedFile;
use App\Traits\CustomHelper;
use App\Traits\GetUserProfilePicture;
use App\Models\MailTemplateAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class TicketCommentController extends Controller
{
    use CustomHelper;
    use GetUserProfilePicture;

    protected $ticketHelper;
    /**
     * Runs when instance is initiated
     */
    public function __construct(TicketHelper $ticketHelper)
    {
        $this->middleware('check.permission:ticket-comment.create', ['only' => ['store']]);
        $this->middleware('check.permission:ticket-comment.edit', ['only' => ['update']]);
        $this->middleware('check.permission:ticket-comment.delete', ['only' => ['destroy']]);
        $this->ticketHelper = $ticketHelper;
    }

    /**
     * Display a listing of the resource.
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function getCommentsByTicket(Request $request, $id)
    {
        $per_page = $request->perPage;
        $model = Comment::where('ticket_id', $id);
        if (!$this->hasRole($request, 'admin')) {
            $model = $model->where('is_archived', 0);
        }
        //for customer portal
        if ($request->visibility) {
            $model = $model->where('visibility', $request->visibility);
        }

        $model = $model->orderBy('created_at', $request->sortOrder ?? 'desc');
        $data = $model->paginate($per_page)->withQueryString()->through(function ($item) {
            return [
                'id' => $item->id,
                'time' => $item->time,
                'text' => $item->text,
                'isAllowedDelete' => isset($item->company) ? false : true,
                'userId' => $item->user_id,
                'isArchived' => $item->is_archived,
                // 'createdAt' => $item->created_at,
                'createdAt' => Carbon::parse($item->created_at)->toDateTimeString(),
                'url' => $this->getProfilePicture($item->user_id),
                'visibility' => $item->visibility ?? '',
                'attachment' => $this->updateURL($item->files),
                'userName' => $item->user_name,
                'informedUsers' => $item->informedUsers->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'userId' => $user->user_id
                    ];
                })
            ];
        });
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Fetch image and return base64 string
     *
     * @param object $files
     */
    public function updateURL($files)
    {
        $updatedFiles = [];

        foreach ($files as $file) {
            try {
                //                $storageFile = Storage::disk('public')->get('public/ticketComment/files/' . $file->storage_name);
                //                $file->base64 = base64_encode($storageFile);
                $updatedFiles[] = $file;
            } catch (\Throwable $exception) {
                // Handle the exception if needed
                // You can log or perform any necessary actions
            }
        }

        return $updatedFiles;
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



    public function processFile($model, $file)
    {
        // Check if the file is present in the request
        if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
            // Get the original file name
            $fileName = $file->getClientOriginalName();

            // Get the file extension
            $fileExtension = $file->getClientOriginalExtension();

            // Define the allowed file types
            $allowedExtensions = ['csv', 'pdf', 'txt'];

            // Check if the file extension is allowed
            if (in_array($fileExtension, $allowedExtensions)) {
                // Generate a unique filename
                $newFileName = uniqid() . '.' . $fileExtension;

                // Store the file
                $file->storeAs('ticketComment/files', $newFileName, 'public');

                // Create a new UploadedFile instance
                $uploadedFile = new \App\Models\UploadedFile();

                // Set the attributes of the UploadedFile model
                $uploadedFile->storage_name = $newFileName;
                $uploadedFile->viewable_name = $fileName;
                $uploadedFile->storage_size = $file->getSize();

                // Associate the UploadedFile model with the main model
                $uploadedFile->fileable()->associate($model);

                // Save the UploadedFile model
                $uploadedFile->save();

                return true;
            }
        }

        return false;
    }

    private function getTicketUserIds($model)
    {
        return collect([
            $model->ticket->user_id,
            $model->ticket->assignee,
            ...$model->ticket->comments()->pluck('user_id')->unique()->values(),
            ...GlobalNotificationList::all()->pluck('user_id')->unique()->values()
        ])
            ->unique()->values();
    }

    public function processImage($model, $attachments)
    {
        foreach ($attachments as $attachment) {
            $original_name = $attachment['name'];
            $base64Decode = base64_decode($attachment['base64'], true);

            // Generate a unique file name
            $file_name_to_store = $model->report_number . '__' . time() . '.' . $original_name;

            // Save the decoded file to disk
            Storage::disk('public')->put('public/ticketComment/files/' . $file_name_to_store, $base64Decode);

            $size = Storage::disk('public')->size('public/ticketComment/files/' . $file_name_to_store);

            $uploaded_file = new UploadedFile();
            $uploaded_file->storage_name = $file_name_to_store;
            $uploaded_file->viewable_name = $original_name;
            $uploaded_file->storage_size = $size;
            $uploaded_file->fileable()->associate($model);
            $uploaded_file->save();
        }
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

    /**
     * Restore the previously deleted source.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $model = Comment::find($id);
        $model->restore();
        return response()->json(['message' => 'Record restored.'], 200);
    }

    /**
     * sends the mail using the Http client
     * @param Request $request
     * @param integer $id
     * @return JSONResponse
     */
    public function sendMail(Request $request, $id)
    {
        try {
            $model = Comment::findOrFail($id);
            $template = MailTemplateAssignment::where('module', 'ticketTemplate')->first();
            if (!$template) {
                throw new \Exception('Mail template is not set for tickets');
            }
            $mails = array_filter(['helpdesk@docshero.de', $model?->visibility == 'internal' ? ($request->isReporterEmployee ? $request->input('reporter') : null) : $request->input('reporter'), $model?->visibility == 'internal' ? ($request->isAssigneeEmployee ? $request->input('assignee') : null) : $request->input('assignee'), ...($request->informedUsers ?? [])], function ($value) {
                return $value !== null;
            });
            $payload = [
                'id' => $template->mail_template_id,
                'mails' => $mails,
                'from' => $template->sender_mail,
                'cc' => $template->cc ? [$template->cc] : [],
                'bcc' => $template->bcc ? [$template->bcc] : [],
                'data' => [
                    'company' => $model->ticket?->company ? [
                        'id' => $model->ticket?->company?->id,
                        'companyName' => $model->ticket?->company?->company_name,
                        'vatId' => $model->ticket?->company?->vat_id,
                        'url' => $model->ticket?->company?->url,
                        'type' => $model->ticket?->company?->type,
                        'customerType' => $model->ticket?->company?->customer_type,
                        'companyNumber' => $model->ticket?->company?->company_number,
                        'state' => $model->ticket?->company?->headQuarters?->state,
                        'city' => $model->ticket?->company?->headQuarters?->city,
                        'country' => $model->ticket?->company?->headQuarters?->country,
                        'address' => $model->ticket?->company?->headQuarters?->address_first,
                        'code' => $model->ticket?->company?->headQuarters?->zip,
                        'notes' => $model->ticket?->company?->notes,
                        'status' => $model->ticket?->company?->status,
                        'mergePdfsOnDefault' => $model->ticket?->company?->merge_pdfs_on_default,
                        'expiryDate' => $model->ticket?->company?->expiry_dt ? Carbon::parse($model->ticket?->company?->expiry_dt)->toDateString() : '',
                        'termsOfPayment' => $model->ticket?->company?->terms_of_payment,
                    ] : null,
                    'ticket' => [
                        'id' => $model->ticket->id,
                        'title' => $model->ticket->title,
                        'type' => $model->ticket->type,
                        'ticketNumber' => $model->ticket->ticket_number,
                        'state' => $model->ticket->state,
                        'priority' => $model->ticket->priority,
                        'userId' => $model->ticket->user_id,
                        'assignee' => $model->ticket->assignee,
                        'contactType' => $model->ticket->contact_type,
                        'companyId' => $model->ticket->company_id,
                        'companyName' => $model->ticket?->company?->company_name,
                        'createdAt' => $model->ticket->created_at,
                        'isArchived' => $model->ticket->is_archived,
                        'visibility' => $model->ticket->visibility ?? '',
                        'totalAccountedTime' => Comment::where('ticket_id', $model->ticket->id)->sum('time'),
                        'ticketComment' => [
                            'id' => $model->id,
                            'time' => $model->time,
                            'text' => $model->text,
                            'isAllowedDelete' => isset($model->company) ? false : true,
                            'userId' => $model->user_id,
                            'isArchived' => $model->is_archived,
                            'createdAt' => $model->created_at,
                            'url' => $this->getProfilePicture($model->user_id),
                            'visibility' => $model->visibility ?? '',
                            'attachment' => $this->updateURL($model->files),
                            'userName' => $model->user_name
                        ]
                    ],
                    'files' => []
                ]
            ];
            $config = config('services.mail');
            $response = Http::withToken($request->bearerToken())
                ->post($config['vite_mail_service_url'] . '/mail-service/send-mail', $payload);
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => isset($response->json()['msg']) ? $response->json()['msg'] : ''
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => isset($response->json()['msg']) ? $response->json()['msg'] : ''
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }

    public function show($id)
    {
        $model = Comment::findOrFail($id);
        $template = MailTemplateAssignment::where('module', 'ticketTemplate')->first();
        if (!$template) {
            return response()->json(['message' => 'Mail template is not set for tickets'], 400);
        }
        $data = [
            'comment' => [
                'company' => $model->company ? [
                    'id' => $model->company?->id,
                    'companyName' => $model->company?->company_name,
                    'vatId' => $model->company?->vat_id,
                    'url' => $model->company?->url,
                    'type' => $model->company?->type,
                    'customerType' => $model->company?->customer_type,
                    'companyNumber' => $model->company?->company_number,
                    'state' => $model->company?->headQuarters?->state,
                    'city' => $model->company?->headQuarters?->city,
                    'country' => $model->company?->headQuarters?->country,
                    'address' => $model->company?->headQuarters?->address_first,
                    'code' => $model->company?->headQuarters?->zip,
                    'notes' => $model->company?->notes,
                    'status' => $model->company?->status,
                    'mergePdfsOnDefault' => $model->company?->merge_pdfs_on_default,
                    'expiryDate' => $model->company?->expiry_dt ? Carbon::parse($model->company?->expiry_dt)->toDateString() : '',
                    'termsOfPayment' => $model->company?->terms_of_payment,
                ] : null,
                'ticket' => [
                    'id' => $model->ticket->id,
                    'title' => $model->ticket->title,
                    'type' => $model->ticket->type,
                    'ticketNumber' => $model->ticket->ticket_number,
                    'state' => $model->ticket->state,
                    'priority' => $model->ticket->priority,
                    'userId' => $model->ticket->user_id,
                    'assignee' => $model->ticket->assignee,
                    'contactType' => $model->ticket->contact_type,
                    'companyId' => $model->ticket->company_id,
                    'createdAt' => $model->ticket->created_at,
                    'isArchived' => $model->ticket->is_archived,
                    'visibility' => $model->ticket->visibility ?? '',
                    'totalAccountedTime' => Comment::where('ticket_id', $model->ticket->id)->sum('time'),
                    'ticketComment' => [
                        'id' => $model->id,
                        'time' => $model->time,
                        'text' => $model->text,
                        'isAllowedDelete' => isset($model->company) ? false : true,
                        'userId' => $model->user_id,
                        'isArchived' => $model->is_archived,
                        'createdAt' => $model->created_at,
                        'url' => $this->getProfilePicture($model->user_id),
                        'visibility' => $model->visibility ?? '',
                        'attachment' => $this->updateURL($model->files),
                        'userName' => $model->user_name
                    ]
                ],
                'files' => []
            ],
            'mailTemplate' => $template
        ];
        return response()->json($data);
    }

    /**
     * Get the base64-encoded content of an attachment by ID.
     *
     * @param  int  $id  The ID of the uploaded file.
     * @return \Illuminate\Http\JsonResponse  JSON response containing base64-encoded content or an error message.
     */
    public function getAttachmentBase64(int $id)
    {
        $model = UploadedFile::find($id);

        if ($model) {
            $storageFile = Storage::disk('public')->get('public/ticketComment/files/' . $model->storage_name);
            return response()->json(['base64' => base64_encode($storageFile)]);
        }

        return response()->json(['message' => 'invalid id provided'], 400);
    }

    public function getAttachmentDownload(int $id)
    {
        $model = UploadedFile::findOrFail($id);
        $file = Storage::disk('public')->path('public/ticketComment/files/' . $model->storage_name);
        return response()->download($file, $model->viewable_name, ['name' => $model->viewable_name]);
    }
}
