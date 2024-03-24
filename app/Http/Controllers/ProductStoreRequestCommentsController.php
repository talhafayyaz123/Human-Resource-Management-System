<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CustomHelper;
use App\Traits\GetUserProfilePicture;
use App\Models\ProductStoreRequestComments as Comment;
use App\Models\UserProfileData;
use App\Models\GlobalNotificationList;
use App\Utils\PermissionChecker;
use Illuminate\Support\Facades\Storage;
use App\Helpers\GlobalSettingHelper;
use App\Models\UploadedFile;
use Carbon\Carbon;
use App\Models\MailTemplateAssignment;

class ProductStoreRequestCommentsController extends Controller
{
    use CustomHelper;
    use GetUserProfilePicture;

      /**
     * Runs when instance is initiated
     */
    public function __construct()
    {
        $this->middleware('check.permission:product-store-request-comment.create', ['only' => ['store']]);
        $this->middleware('check.permission:product-store-request-comment.edit', ['only' => ['update']]);
        $this->middleware('check.permission:product-store-request-comment.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function getCommentsByProductStoreRequest(Request $request, $id)
    {
        $per_page = $request->perPage;
        $model = Comment::where('product_request_store_id', $id);
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
                'createdAt' => $item->created_at,
                'url' => $this->getProfilePicture($item->user_id),
                'visibility' => $item->visibility ?? '',
                'attachment' => $this->updateURL($item->files),
                'userName' => $item->user_name
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => ['required'],
            'userName' => 'nullable|string',
            'userMail' => 'nullable|email',
            'visibility' => 'required',
            'time' => [
                'required',
                'max:5',
            ]
        ]);

        //Create Ticket Comment
        $model = new Comment();
        $saveData = $this->saveData($model, $request, 'create');

        $userProfileData = UserProfileData::where('user_id', $saveData->user_id)->first();
        return response()->json(
            [
                'message' => 'Record created successfully.',
                'id' => $model->id,
                //'ProductStoreUsers' => $this->getTicketUserIds($model),
                'productStore' => $model->toArray(),
                'userName' => $userProfileData?->full_name,
                'userId' => $saveData->user_id,
            ],
            200
        );
    }

    public function saveData($model, $request, $action)
    {
        $model->text = $request->text;
        $model->user_id = $this->getAuthUserId($request) ?? null;

        if ($request->userId && PermissionChecker::isAdmin($request)) {
            $model->user_id = $request->userId;
        }

        $model->user_type = $request->userType;
        $model->time = $request->time;
        $model->visibility = $request->visibility ?? null;
        $model->product_request_store_id = $request->productRequestStoreId;
        $model->user_name = $request->userName ?? '';
        $model->user_email = $request->userMail ?? '';
        $model->save();

        if ($action == 'create' && $model->visibility == 'public' && $request->fromAdmin) {
           // $model->ticket->state = 'open';
            //$model->ticket->save();
        }

        // Save attachment
        if ($request->has('attachment') && $request->input('attachment') != '') {
            $this->processImage($model, $request->input('attachment'));
        }
        $content = [
            'moduleAction' => 'createComment',
            'data' => $model->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return $model;
    }

     public function processImage($model, $attachments)
    {
        foreach ($attachments as $attachment) {
            $original_name = $attachment['name'];
            $base64Decode = base64_decode($attachment['base64'], true);

            // Generate a unique file name
            $file_name_to_store = $model->report_number . '__' . time() . '.' . $original_name;

            // Save the decoded file to disk
            Storage::disk('public')->put('public/productStoreStoreComment/files/' . $file_name_to_store, $base64Decode);

            $size = Storage::disk('public')->size('public/productStoreStoreComment/files/' . $file_name_to_store);

            $uploaded_file = new UploadedFile();
            $uploaded_file->storage_name = $file_name_to_store;
            $uploaded_file->viewable_name = $original_name;
            $uploaded_file->storage_size = $size;
            $uploaded_file->fileable()->associate($model);
            $uploaded_file->save();
        }
    }

    private function getTicketUserIds($model)
    {
        return collect([$model->product_store->author_id ?? 0,
            $model->product_store->assignee ?? '',
            ...$model->product_store->comments()->pluck('user_id')->unique()->values(),
            ...GlobalNotificationList::all()->pluck('user_id')->unique()->values()])
            ->unique()->values();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Comment::findOrFail($id);
        $template = MailTemplateAssignment::where('module', 'productStoreRequestTemplate')->first();
        if (!$template) {
            return response()->json(['message' => 'Mail template is not set for prodyct store requests'], 400);
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
                'productRequest' => [
                    'id' => $model->product_store->id,
                    'title' => $model->product_store->product_title,
                  //  'type' => $model->product_store->type,
                    'itemNumber' => $model->product_store->item_number,
                    //'state' => $model->product_store->state,
                    //'priority' => $model->product_store->priority,
                    'userId' => $model->product_store->author_id,
                    //'assignee' => $model->product_store->assignee,
                  //  'contactType' => $model->product_store->contact_type,
                    //'companyId' => $model->product_store->company_id,
                    'createdAt' => $model->product_store->created_at,
                   // 'isArchived' => $model->product_store->is_archived,
                    'partnerVisibility' => $model->product_store->is_visible_for_partner ?? '',
                    'customerVisibility' => $model->product_store->is_visible_for_customer ?? '',
                    'totalAccountedTime' => Comment::where('product_request_store_id', $model->product_store->id)->sum('time'),
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'text' => 'required',
            'visibility' => 'required',
            'time' => [
                'required',
                'max:5',
            ]
        ]);

        //Update Ticket Comment
        $model = Comment::where('id', $id)->first();
        $this->saveData($model, $request, 'update');
        return response()->json(
            [
                'message' => 'Record updated successfully.',
              //  'ProductStoreUsers' => $this->getTicketUserIds($model),
                'ticket' => $model->toArray(),
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Comment::find($id);
        $model->is_archived = true;
        $model->save();
        $model->deleted_at = Carbon::now()->toIso8601ZuluString();
        $content = [
            'moduleAction' => 'deleteComment',
            'data' => $model->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => 'Record deleted.'], 200);
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
     * Get the base64-encoded content of an attachment by ID.
     *
     * @param  int  $id  The ID of the uploaded file.
     * @return \Illuminate\Http\JsonResponse  JSON response containing base64-encoded content or an error message.
     */
    public function getAttachmentBase64(int $id)
    {
        $model = UploadedFile::find($id);

        if ($model) {
            $storageFile = Storage::disk('public')->get('public/productStoreStoreComment/files/' . $model->storage_name);
            return response()->json(['base64' => base64_encode($storageFile)]);
        }

        return response()->json(['message' => 'invalid id provided'], 400);
    }

    public function getAttachmentDownload(int $id)
    {
        $model = UploadedFile::findOrFail($id);
        
        $file = Storage::disk('public')->path('public/productStoreStoreComment/files/' . $model->storage_name);
        return response()->download($file, $model->viewable_name, ['name' => $model->viewable_name]);
    }

}
