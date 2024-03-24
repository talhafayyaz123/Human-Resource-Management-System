<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalSettingHelper;
use App\Http\Resources\ModuleHistoryResource;
use App\Models\GlobalSetting;
use App\Models\ExecutiveBoard;
use App\Models\InvoiceReminderLevel;
use App\Models\ModuleHistory;
use App\Models\UserProfileData;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\MyFeed\MyFeedService;
use App\Http\Resources\MyFeed\MyFeedResource;

class GlobalSettingController extends Controller
{

    use CustomHelper;
    protected $myFeedService;

    /**
     * Run on instantiate
     */
    public function __construct(MyFeedService $myFeedService)
    {
        $this->middleware('check.permission:global-setting.list', ['only' => ['index']]);
        $this->middleware('check.permission:global-setting.save', ['only' => ['save']]);
        $this->middleware('check.permission:cip-configuration.list', ['only' => ['cipConfigurationList']]);
        $this->middleware('check.permission:cip-configuration.save', ['only' => ['cipConfigurationSave']]);
        $this->myFeedService = $myFeedService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = GlobalSetting::where("key", "LIKE", "expiryMonth")->first();
        return response()->json([
            'expiryMonth' => $model?->value,
            'expiryDate' => Carbon::now()->month($model?->value)->endOfMonth()->format('d-m-Y')
        ], 200);
    }

    /**
     * Save global settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $request->validate([
            "expiryMonth" => "required",
        ]);

        $model = GlobalSetting::firstOrNew(['key' => 'expiryMonth']);
        $model->value = $request->expiryMonth;
        $model->save();
        return response()->json(['message' => 'Record Saved.'], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function documentAssignmentList(Request $request)
    {
        $model = GlobalSetting::where("key", "LIKE", "contactReportTemplateId")
            ->orWhere("key", "LIKE", "invoiceTemplateId")
            ->orWhere("key", "LIKE", "invoiceCorrectionTemplateId")
            ->orWhere("key", "LIKE", "invoiceStornoTemplateId")
            ->orWhere("key", "LIKE", "offerTemplateId")
            ->orWhere("key", "LIKE", "perfRecordTemplateId")
            ->orWhere("key", "LIKE", "orderConfirmationTemplateId")
            ->orWhere("key", "LIKE", "projectProtocolTemplateId")
            ->orWhere("key", "LIKE", "serviceReportTemplateId")
            ->orWhere("key", "LIKE", "orderConfirmationTemplateId");

        $invoice_reminder_levels = InvoiceReminderLevel::get();
        $invoice_reminder_levels->map(function ($data) use ($model) {
            $model->orWhere("key", "LIKE", $data->level_name);
        });
        $model = $model->get();

        $response = [];
        foreach ($model as $item) {
            $response[$item->key] = $item->value;
        }
        return response()->json($response, 200);
    }

    /**
     * Save global settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function documentAssignmentSave(Request $request)
    {
        $request->validate([
            "contactReportTemplateId" => "required",
            "invoiceTemplateId" => "required",
            "invoiceCorrectionTemplateId" => "required",
            "invoiceStornoTemplateId" => "required",
            "offerTemplateId" => "required",
            "perfRecordTemplateId" => "required",
            "orderConfirmationTemplateId" => "required",
            "invoiceWarningLevels" => "nullable|array",
            "invoiceWarningLevels.*.label" => "required",
            "invoiceWarningLevels.*.reminderTemplateId" => "required"
        ]);

        $model = GlobalSetting::firstOrNew(['key' => 'contactReportTemplateId']);
        $model->value = $request->contactReportTemplateId;
        $model->save();

        $model = GlobalSetting::firstOrNew(['key' => 'invoiceTemplateId']);
        $model->value = $request->invoiceTemplateId;
        $model->save();

        $model = GlobalSetting::firstOrNew(['key' => 'invoiceCorrectionTemplateId']);
        $model->value = $request->invoiceCorrectionTemplateId;
        $model->save();

        $model = GlobalSetting::firstOrNew(['key' => 'invoiceStornoTemplateId']);
        $model->value = $request->invoiceStornoTemplateId;
        $model->save();

        $model = GlobalSetting::firstOrNew(['key' => 'offerTemplateId']);
        $model->value = $request->offerTemplateId;
        $model->save();

        $model = GlobalSetting::firstOrNew(['key' => 'perfRecordTemplateId']);
        $model->value = $request->perfRecordTemplateId;
        $model->save();

        $model = GlobalSetting::firstOrNew(['key' => 'orderConfirmationTemplateId']);
        $model->value = $request->orderConfirmationTemplateId;
        $model->save();

        $model = GlobalSetting::firstOrNew(['key' => 'projectProtocolTemplateId']);
        $model->value = $request->projectProtocolTemplateId;
        $model = GlobalSetting::firstOrNew(['key' => 'serviceReportTemplateId']);
        $model->value = $request->serviceReportTemplateId;
        $model->save();
        if (!empty($request->invoiceWarningLevels)) {
            $warning_levels = collect($request->invoiceWarningLevels);
            $warning_levels->map(function ($data) {
                if (isset($data['reminderTemplateId']) && isset($data['label'])) {
                    $model = GlobalSetting::firstOrNew(['key' => $data['label']]);
                    $model->value = $data['reminderTemplateId'];
                    $model->save();
                }
            });
        }


        return response()->json(['message' => 'Record Saved.'], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mailTemplateAssignmentList(Request $request)
    {
        $model = GlobalSetting::where("key", "LIKE", "mailTemplateId")->first();
        return response()->json($model, 200);
    }

    /**
     * Save global settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mailTemplateAssignmentSave(Request $request)
    {
        $request->validate([
            "mailTemplateId" => "required",
        ]);

        $model = GlobalSetting::firstOrNew(['key' => 'mailTemplateId']);
        $model->value = $request->mailTemplateId;
        $model->save();

        return response()->json(['message' => 'Record Saved.'], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cipConfigurationList(Request $request)
    {
        $model = GlobalSetting::where("key", "LIKE", "cipManager")
            ->orWhere("key", "LIKE", "qualityManagementOfficer")
            ->get();

        $response = [];
        foreach ($model as $item) {
            $user_profile = UserProfileData::where("id", $item->value)->first();
            if ($user_profile) {
                $response[$item->key] = [
                    'id' => $user_profile->id,
                    'employee' => $user_profile->first_name . ' ' . $user_profile->last_name,
                    'firstName' => $user_profile->first_name,
                    'lastName' => $user_profile->last_name,
                    'email' => $user_profile->email,
                    'userId' => $user_profile->user_id,
                    'dateOfBirth' => $user_profile->date_of_birth,
                    'teams' => [],
                    'departments' => '',
                    "personalNumber" => $user_profile->jobData->personal_number ?? '',
                    "jobTitle" => $user_profile->jobData->job_title ?? '',
                    "joinDate" => $user_profile->jobData->join_date ?? '',
                    "location" => $user_profile->jobData->location?->name ?? '',
                    "exitDate" => $user_profile->jobData->exit_date ?? '',
                    "grossSalary" => $user_profile->userCompensationData->gross_monthly_salary ?? '',
                    "profilePicUrl" => ''
                ];
            }
        }

        $response['executiveBoard'] = ExecutiveBoard::all()->map(function ($member) {
            return $member->user ? [
                'id' => $member->user->id,
                'employee' => $member->user->first_name . ' ' . $member->user->last_name,
                'firstName' => $member->user->first_name,
                'lastName' => $member->user->last_name,
                'email' => $member->user->email,
                'userId' => $member->user->user_id,
                'dateOfBirth' => $member->user->date_of_birth,
                'teams' => [],
                'departments' => '',
                "personalNumber" => $member->user?->jobData?->personal_number ?? '',
                "jobTitle" => $member->user?->jobData?->job_title ?? '',
                "joinDate" => $member->user?->jobData?->join_date ?? '',
                "location" => $member->user?->jobData?->location?->name ?? '',
                "exitDate" => $member->user?->jobData?->exit_date ?? '',
                "grossSalary" => $member->user?->userCompensationData?->gross_monthly_salary ?? '',
                "profilePicUrl" => ''
            ] : null;
        });

        return response()->json($response, 200);
    }

    /**
     * Save global settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cipConfigurationSave(Request $request)
    {
        $request->validate([
            "cipManager" => "required",
            "qualityManagementOfficer" => "required",
            "executiveBoard" => "required|array",
            "executiveBoard.*id" => "required",
        ]);

        $model = GlobalSetting::firstOrNew(['key' => 'cipManager']);
        $model->value = $request->cipManager;
        $model->save();

        $model = GlobalSetting::firstOrNew(['key' => 'qualityManagementOfficer']);
        $model->value = $request->qualityManagementOfficer;
        $model->save();

        ExecutiveBoard::all()->each->delete();

        foreach ($request->executiveBoard as $member) {
            ExecutiveBoard::create(["user_id" => $member["id"]]);
        }

        return response()->json(['message' => 'Record Saved.'], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTemplateByName(Request $request, $name)
    {
        $model = GlobalSetting::where("key", "LIKE", $name)->first();
        if (empty($model->value)) {
            return response()->json([
                "message" => "Please assign a template document for this module"
            ], 422);
        }
        return response()->json([
            'name' => $model->value,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function eloConfigurationList()
    {
        $model = GlobalSetting::where("key", "LIKE", "eloConfigUsername")
            ->orWhere("key", "LIKE", "eloConfigPassword")
            ->orWhere("key", "LIKE", "eloConfigUrl")
            ->get();

        $response = [];
        foreach ($model as $item) {
            $response[$item->key] = $item->value;
        }
        return response()->json($response, 200);
    }

    /**
     * Save global settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function eloConfigurationSave(Request $request)
    {
        $request->validate([
            "eloConfigUsername" => "required",
            "eloConfigPassword" => "required",
            "eloConfigUrl" => "required",
        ]);

        $model = GlobalSetting::firstOrNew(['key' => 'eloConfigUsername']);
        $model->value = $request->eloConfigUsername;
        $model->save();

        $model = GlobalSetting::firstOrNew(['key' => 'eloConfigPassword']);
        $model->value = $request->eloConfigPassword;
        $model->save();

        $model = GlobalSetting::firstOrNew(['key' => 'eloConfigUrl']);
        $model->value = $request->eloConfigUrl;
        $model->save();

        return response()->json(['message' => 'Record Saved.'], 200);
    }

    /**
     * Send API request based on provided content
     */
    public function sendEloAPIRequest(Request $request)
    {
        $request->validate([
            "content" => "required",
        ]);
        GlobalSettingHelper::sendEloAPIRequest($request->content);
        return response()->json(['message' => 'ELO request sent.'], 200);
        //        $model = GlobalSetting::where("key", "LIKE", "eloConfigUsername")
        //            ->orWhere("key", "LIKE", "eloConfigPassword")
        //            ->orWhere("key", "LIKE", "eloConfigUrl")
        //            ->get();
        //        $response = [];
        //        foreach ($model as $item) {
        //            $response[$item->key] = $item->value;
        //        }
        //        if (isset($response["eloConfigUsername"]) && isset($response["eloConfigPassword"]) && isset($response["eloConfigUrl"])) {
        //            $response = Http::withHeaders([
        //                'Authorization' => 'Basic ' . base64_encode($response["eloConfigUsername"] . ":" . $response["eloConfigPassword"]),
        //            ])->post($response["eloConfigUrl"], [
        //                "content" => $request->content
        //            ]);
        //            return response()->json(['message' => 'ELO request sent.'], 200);
        //        }
        //        return response()->json(['message' => 'Missing configuration for ELO.'], 422);
    }

    /**
     * Save global settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendCoverLetterTextRequest(Request $request)
    {
        $request->validate([
            "commentText" => "required",
        ]);

        $model = GlobalSetting::firstOrNew(['key' => 'coverLetterText']);
        $model->value = $request->commentText;
        $model->save();
        return response()->json(['message' => 'Record Saved.'], 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function coverLetterText()
    {
        $model = GlobalSetting::where("key", "LIKE", "coverLetterText")->first();
        return response()->json([
            'commentText' => $model?->value,
        ], 200);
    }

    public function storePartnerDiscount(Request $request)
    {
        $request->validate([
            "discountRate" => "required|numeric",
        ]);

        $model = GlobalSetting::firstOrNew(['key' => 'partnerDiscountRate']);
        $model->value = $request->discountRate;
        $model->save();
        return response()->json(['message' => 'Record Saved.'], 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPartnerDiscount()
    {
        $model = GlobalSetting::where("key", "LIKE", "partnerDiscountRate")->first();
        return response()->json([
            'partnerDiscountRate' => $model?->value,
        ], 200);
    }

    /**
     * Save global settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendOfferConfirmationCoverLetterTextRequest(Request $request)
    {
        $request->validate([
            "commentText" => "required",
        ]);

        $model = GlobalSetting::firstOrNew(['key' => 'offerConfirmationCoverLetterText']);
        $model->value = $request->commentText;
        $model->save();
        return response()->json(['message' => 'Record Saved.'], 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function offerConfirmationCoverLetterText()
    {
        $model = GlobalSetting::where("key", "LIKE", "offerConfirmationCoverLetterText")->first();
        return response()->json([
            'commentText' => $model?->value,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function moduleHistory(Request $request, $id)
    {
        $perPage = $request->perPage ?? 5;
        $moduleType = "App\Models\\" . $request->moduleName;

        $moduleHistory = ModuleHistory::where('moduleable_type', $moduleType)->where('moduleable_id', $id)
            ->orderBy('id', 'desc')->paginate($perPage);

            $feeds = $this->myFeedService->getTrashedModuleFeed([
                'moduleId' => $id,
                'moduleName' => $moduleType,
                'search' => $request->search ?? '',
                'mentionUserId' => $request->mentionUserId ?? '',
                'perPage' => $request->perPage ?? 20,
            ]);

        return response()->json([
            'data' => ModuleHistoryResource::collection($moduleHistory),
            'feeds' =>  MyFeedResource::collection($feeds),
            'links' => $moduleHistory->links(),
            'current_page' => $moduleHistory->currentPage(),
            'from' => $moduleHistory->firstItem(),
            'last_page' => $moduleHistory->lastPage(),
            'path' => request()->url(),
            'per_page' => $moduleHistory->perPage(),
            'to' => $moduleHistory->lastItem(),
            'total' => $moduleHistory->total(),
        ], 200);
    }
}
