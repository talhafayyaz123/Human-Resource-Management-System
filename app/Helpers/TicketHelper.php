<?php

namespace App\Helpers;

use App\Models\Company;
use App\Models\GlobalNotificationList;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketComment as Comment;
use App\Models\TimeTrackerCompany;
use App\Models\UploadedFile;
use App\Models\UserProfileData;
use App\Traits\CustomHelper;
use App\Traits\GetUserProfilePicture;
use App\Traits\SetGlobalNumber;
use App\Utils\PermissionChecker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as staticRequest;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class TicketHelper
{
    use CustomHelper;
    use SetGlobalNumber;
    use GetUserProfilePicture;

    public function index($request, $customerType = 'customer')
    {
        $per_page = $request->perPage ?? 10;

        $model = new Ticket();

        if ($request->companyId) {
            $model = $model->where('company_id', $request->companyId);
        }

        if ($request->assigneeId) {
            $model = $model->where('assignee', $request->assigneeId);
        }

        if ($request->userId) {
            $model = $model->where('user_id', $request->userId);
        }

        if ($request->area && $request->area != 'all') {
            $model = $model->where('area', $request->area);
        }

        if ($request->state) {
            $model = $model->where('state', $request->state);
        } else if ($request->isCustomer != true) {
            $model = $model->where('state', '!=', 'resolved');
        } else if($request->forPartnerPortal != true) {
            $model = $model->where('state', '!=', 'resolved');
        }

        if ($request->priority) {
            $model = $model->where('priority', $request->priority);
        }

        if ($request->visibility) {
            $model = $model->where('visibility', $request->visibility);
        }

        if ($customerType) {
            $model = $model->where('customer_type', $customerType);
        }

        if ($request->unaccountedTickets) {
            $model = $model->whereHas('comments', function ($query) {
                $query->where('is_added', false);
                $query->whereNotNull('time');
            });
        }

        if ($request->forPartnerPortal) {
            $partnerCustomer = Company::where('partner_id', $request->partnerId)->pluck('id')->toArray();
             if ($request->partnerArea == 'all') {
                 $model = $model->where(function ($query) use ($partnerCustomer, $request){
                     $query->whereIn('company_id', $partnerCustomer)->where('area', 'customer');
                     $query->orWhere('company_id', $request->partnerId)->where('area', 'partner');
                     $query->orWhere('company_id', $request->partnerId)->where('area', 'product');
                 });
             } else if($request->partnerArea == 'partner' || $request->partnerArea == 'product') {
                 $model = $model->where('company_id', $request->partnerId)->where('area', $request->partnerArea);
             } else if($request->partnerArea == 'customer') {
                 $model = $model->whereIn('company_id', $partnerCustomer)->where('area', $request->partnerArea);
             }
        }


        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        $models = $model->filter(staticRequest::only('search', 'state', 'priority', 'assigneeId', 'isArchived'))->paginate($per_page);

        $model_collect = $models->map(function ($item) {
            // get ticket comment ids
            $ticket_ids = $item->comments->pluck('id');
            // if count of ticket comment in time tracker company module id is equal to 0, then ticket is deletable
            $isDeletable = TimeTrackerCompany::where('module_type', '=', "App\Models\TicketComment")->whereIn('module_id', $ticket_ids)->count() == 0;
            $last_comment_date = $item->comments()->orderBy('created_at', 'desc')->first()?->created_at;
            return [
                'id' => $item->id,
                'title' => $item->title,
                'type' => $item->type,
                'ticketNumber' => $item->ticket_number,
                'state' => $item->state,
                'area' => $item->area,
                'priority' => $item->priority,
                'userId' => $item->user_id,
                'assignee' => $item->assignee,
                // 'userType' => Auth::user()->type,
                'contactType' => $item->contact_type,
                'companyId' => $item->company_id,
                'companyName' => $item->company?->company_name ?? '',
                'createdAt' => $item->created_at,
                'isArchived' => $item->is_archived,
                'visibility' => $item->visibility ?? '',
                'totalAccountedTime' => TicketComment::where('ticket_id', $item->id)->where('is_archived', 0)->sum('time'),
                'isDeletable' => $isDeletable,
                'lastCommentDate' => $last_comment_date ? Carbon::parse($last_comment_date)->toDateTimeString() : null
            ];
        });

        return response()->json([
            'data' => $model_collect,
            'links' => $models->links(),
            'meta' => [
                'current_page' => $models->currentPage(),
                'from' => $models->firstItem(),
                'last_page' => $models->lastPage(),
                'path' => request()->url(),
                'per_page' => $models->perPage(),
                'to' => $models->lastItem(),
                'total' => $models->total(),
            ]
        ], 200);
    }


    public function store($request, $customerType = 'customer')
    {
        $area = $customerType;
        if ($request->area) {
            $area = $request->area;
        }
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'state' => 'required',
            'priority' => 'required',
            'companyId' => Rule::RequiredIf(function () use ($area) {
                if ($area == 'customer' || $area == 'partner') {
                    return true;
                }
                return false;
            }),
            'productId' => 'nullable',
            'contactType' => 'required',
            'visibility' => 'required',
            'text' => 'required',
        ]);
        $ticket_id = null;
        $ticket_comment_id = null;
        $model_comment = collect([]);
        $model_ticket = collect([]);
        try {
            DB::transaction(function () use ($request, &$ticket_id, &$ticket_comment_id, &$model_comment, &$model_ticket, &$customerType) {
                $model_ticket = new Ticket();
                $model_ticket->title = $request->title;
                $model_ticket->type = $request->type;

                if ($customerType == 'partner'){
                    $number = $this->globalNumber($model_ticket, 'partnerTicket', '#', 100000, 'ticket');
                }else{
                    $number = $this->globalNumber($model_ticket, 'ticket', '#', 100000, 'ticket');
                }

                $model_ticket->ticket_number = $number;
                $model_ticket->state = $request->state;
                $model_ticket->priority = $request->priority;
                $model_ticket->contact_type = $request->contactType;
                $model_ticket->assignee = $request->assignee;
                //Morph data
                $model_ticket->user_id = $request->get('userId') ?? $this->getAuthUserId($request);
                $model_ticket->user_type = $request->userType;
                //Association with company
                $model_ticket->company_id = $request->companyId;
                $model_ticket->product_id = $request->productId;
                $model_ticket->visibility = $request->visibility ?? null;
                $model_ticket->software_id = $request->softwareId ?? null;
                $model_ticket->ams_id = $request->amsId ?? null;
                $model_ticket->customer_type = $customerType;
                $model_ticket->area = $request->area ?? $customerType;
                $model_ticket->save();
                $content = [
                    'moduleAction' => 'createTicket',
                    'data' => $model_ticket->toArray(),
                ];
                GlobalSettingHelper::sendEloAPIRequest($content);
                $ticket_id = $model_ticket->id;

                $model_comment = new TicketComment();
                $model_comment->text = $request->text;
                $model_comment->time = $request->time;
                $model_comment->ticket_id = $ticket_id;
                $model_comment->visibility = $request->visibility ?? null;
                //Morph data
                $model_comment->user_id = $request->get('userId') ?? $this->getAuthUserId($request);
                $model_comment->user_type = $request->userType;
                $model_comment->save();
                // Save attachment
                if ($request->has('attachment') && $request->input('attachment') != '') {
                    $this->processImage($model_comment, $request->input('attachment'));
                }
                $ticket_comment_id = $model_comment->id;
                $content = [
                    'moduleAction' => 'createTicketComment',
                    'data' => $model_comment->toArray(),
                ];
                GlobalSettingHelper::sendEloAPIRequest($content);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
        $userProfileData = UserProfileData::where('user_id', $model_ticket->user_id)->first();
        return response()->json([
            'message' => 'Record created successfully.',
            'ticketId' => $ticket_id,
            'ticketTitle' => $model_ticket->title,
            'ticketNumber' => $model_ticket->ticket_number,
            'userName' => $userProfileData?->full_name,
            'userId' => $model_ticket->user_id,
            'ticketCommentId' => $ticket_comment_id,
            'ticketUsers' => $this->getTicketUserIds($model_comment),
            'ticket' => $model_comment->toArray(),
        ], 200);
    }

    public function show($id)
    {
        $model = Ticket::where('id', $id)->orWhere('ticket_number', '#' . $id)->first();
        $assignee = UserProfileData::where('user_id', $model->assignee)->first();
        return response()->json([
            'tickets' => [
                'id' => $model->id,
                'title' => $model->title,
                'type' => $model->type,
                'ticketNumber' => $model->ticket_number,
                'state' => $model->state,
                'priority' => $model->priority,
                'contactType' => $model->contact_type,
                'userId' => $model->user_id,
                'isArchived' => $model->is_archived,
                'assignee' => $model->assignee,
                'area' => $model->area,
                'assigneeProfilePic' => $this->getProfilePicture($model->assignee),
                'reporterProfilePic' => $this->getProfilePicture($model->user_id),
                'assigneeName' => ($assignee?->first_name ?? '') . ' ' . ($assignee?->last_name ?? ''),
                // 'userType' => Auth::user()->type,
                'companyId' => $model->company_id,
                'companyDetails' => $model->company ? [
                    'id' => $model->company->id,
                    'companyName' => $model->company->company_name,
                    'vatId' => $model->company->vat_id,
                    'url' => $model->company->url,
                    'type' => $model->company->type,
                    'customerType' => $model->company->customer_type,
                    'companyNumber' => $model->company->company_number,
                    'state' => $model->company->headQuarters->state,
                    'city' => $model->company->headQuarters->city,
                    'country' => $model->company->headQuarters->country,
                    'address' => $model->company->headQuarters->address_first,
                    'code' => $model->company->headQuarters->zip,
                ] : null,
                'productId' => $model->product_id,
                'productDetail' => $model->product ? [
                    'id' => $model->product->id,
                    'articleNumber' => $model->product->article_number,
                    'name' => $model->product->name,
                ] : null,
                'createdAt' => Carbon::parse($model->created_at)->toDateTimeString(),
                'visibility' => $model->visibility ?? '',
                'totalAccountedTime' => $model->comments()->where('is_archived', 0)->sum('time'),
                'software' => $model->software ? [
                    'id' => $model->software->id,
                    'name' => $model->software->name,
                ] : null,
                'ams' => $model->ams ? [
                    'id' => $model->ams->id,
                    'amsNumber' => $model->ams->ams_number,
                    'serviceHoursYear' => $model->ams->service_hours_year,
                    'remainingHours' => $model->ams->remaining_hours,
                    'hourlyRate' => $model->ams->hourly_rate,
                    'monthlyAmount' => $model->ams->monthly_amount,
                    'attachment' => $model->ams->contractAttachment ? [
                        'id' => $model->ams->contractAttachment->id,
                        'name' => $model->ams->contractAttachment->attachment->name,
                        'prefix' => $model->ams->contractAttachment->attachment->prefix,
                        'version' => $model->ams->contractAttachment->attachment->version,
                        'allowToAddSlas' => $model->ams->contractAttachment->attachment->allow_to_add_slas,
                        'slaInfrastructureId' => $model->ams->contractAttachment->slaInfrastructure ? [
                            'id' => $model->ams->contractAttachment->slaInfrastructure->id,
                            'name' => $model->ams->contractAttachment->slaInfrastructure->name,
                            'category' => $model->ams->contractAttachment->slaInfrastructure->category
                        ] : null,
                        'slaInfrastructureUserSupport' => $model->ams->contractAttachment->sla_infrastructure_user_support,
                        'slaServiceTimeId' => $model->ams->contractAttachment->slaServiceTime ? [
                            'id' => $model->ams->contractAttachment->slaServiceTime->id,
                            'name' => $model->ams->contractAttachment->slaServiceTime->name,
                            'days' => $model->ams->contractAttachment->slaServiceTime->days,
                            'from' => $model->ams->contractAttachment->slaServiceTime->from,
                            'to' => $model->ams->contractAttachment->slaServiceTime->to,
                            'factor' => $model->ams->contractAttachment->slaServiceTime->factor,
                        ] : null,
                        'slaLevelId' => $model->ams->contractAttachment->slaLevel ? [
                            'id' => $model->ams->contractAttachment->slaLevel->id,
                            'name' => $model->ams->contractAttachment->slaLevel->name,
                            'reactionTimeUrgent' => $model->ams->contractAttachment->slaLevel->reaction_time_urgent,
                            'reactionTimeHigh' => $model->ams->contractAttachment->slaLevel->reaction_time_high,
                            'reactionTimeLow' => $model->ams->contractAttachment->slaLevel->reaction_time_low,
                        ] : null,
                    ] : null
                ] : null,
            ]
        ]);
    }

    public function update($request, $id)
    {
        $request->validate([
            'type' => 'required',
            'title' => 'required',
            'state' => 'required',
            'priority' => 'required',
            'companyId' => 'nullable',
            'productId' => 'nullable',
            'contactType' => 'required',
            'visibility' => 'required',
        ]);

        //Update Ticket
        $model = Ticket::where('id', $id)->first();

        $ticket_id = $this->saveData($model, $request);

        return response()->json([
            'message' => 'Record updated successfully.',
            'ticketId' => $ticket_id
        ], 200);
    }


    /**
     * Store the images while creating the ticket first comment.
     *
     * @param $model
     * @param $attachments
     */
    public function processImage($model, $attachments): void
    {
        foreach ($attachments as $attachment) {
            $original_name = $attachment['name'];
            $base64Decode = base64_decode($attachment['base64'], true);

            // Generate a unique file name
            $file_name_to_store = $model->id . '__' . time() . '.' . $original_name;

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

    /**
     * Saves the data based on provided resource item
     *
     * @param object $model
     * @param object $request
     * @param array   Response
     */
    public function saveData($model, $request)
    {
        $model->title = $request->title;
        $model->type = $request->type;
        $model->state = $request->state;
        $model->priority = $request->priority;
        $model->contact_type = $request->contactType;
        $model->assignee = $request->assignee;
        $model->software_id = $request->softwareId ?? null;
        $model->visibility = $request->visibility ?? null;
        $model->ams_id = $request->amsId ?? null;
        $model->user_id = $request->userId;
        $model->product_id = $request->productId;
        if ($request->area) {
            $model->area = $request->area;
        }

        //Morph data
        $model->user_type = 'somevalue';

        //Association with company
        $model->company_id = $request->companyId;
        $model->save();
        $content = [
            'moduleAction' => 'updateTicket',
            'data' => $model->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return $model->id;
    }


    public function destroy($id)
    {
        $model = Ticket::find($id);
        $model->is_archived = true;
        $model->save();
        $model->deleted_at = Carbon::now()->toIso8601ZuluString();
        $content = [
            'moduleAction' => 'deleteTicket',
            'data' => $model->toArray(),
        ];
        GlobalSettingHelper::sendEloAPIRequest($content);
        return response()->json(['message' => 'Record deleted.'], 200);
    }


    public function commentStore(Request $request)
    {
        $request->validate([
            'text' => ['required'],
            'userName' => 'nullable|string',
            'userMail' => 'nullable|email',
            'visibility' => 'required',
            'time' => [
                'required',
                'max:5',
            ],
            'informedUsers' => 'nullable|array',
            'informedUsers.*.userId' => 'required|string'
        ]);

        //Create Ticket Comment
        $model = new Comment();
        $saveData = $this->commentSaveData($model, $request, 'create');
        foreach ($request->informedUsers ?? [] as $user) {
            $saveData->informedUsers()->create([
                'user_id' => $user['userId'],
            ]);
        };
        $userProfileData = UserProfileData::where('user_id', $saveData->user_id)->first();
        return response()->json(
            [
                'message' => 'Record created successfully.',
                'id' => $model->id,
                'ticketUsers' => $this->getTicketUserIds($model),
                'ticket' => $model->toArray(),
                'userName' => $userProfileData?->full_name,
                'userId' => $saveData->user_id,
            ],
            200
        );
    }


    public function commentUpdate(Request $request, $id)
    {
        $request->validate([
            'text' => 'required',
            'visibility' => 'required',
            'time' => [
                'required',
                'max:5',
            ],
            'informedUsers' => 'nullable|array',
            'informedUsers.*.userId' => 'required|string'
        ]);

        //Update Ticket Comment
        $model = Comment::where('id', $id)->first();
        $this->commentSaveData($model, $request, 'update');
        $model->informedUsers->each->delete();
        foreach ($request->informedUsers ?? [] as $user) {
            $model->informedUsers()->create([
                'user_id' => $user['userId'],
            ]);
        };
        return response()->json(
            [
                'message' => 'Record updated successfully.',
                'ticketUsers' => $this->getTicketUserIds($model),
                'ticket' => $model->toArray(),
            ],
            200
        );
    }


    /**
     * Saves the data based on provided resource item
     *
     * @param object $model
     * @param object $request
     * @param array   Response
     */
    public function commentSaveData($model, $request, $action)
    {
        $model->text = $request->text;
        $model->user_id = $this->getAuthUserId($request) ?? null;

        if ($request->userId && PermissionChecker::isAdmin($request)) {
            $model->user_id = $request->userId;
        }

        $model->user_type = $request->userType;
        $model->time = $request->time;
        $model->visibility = $request->visibility ?? null;
        $model->ticket_id = $request->ticketId;
        $model->user_name = $request->userName ?? '';
        $model->user_email = $request->userMail ?? '';
        $model->save();

        if ($action == 'create' && $model->visibility == 'public' && $request->fromAdmin) {
            $model->ticket->state = 'open';
            $model->ticket->save();
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

    public function commentDestroy($id)
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
}
