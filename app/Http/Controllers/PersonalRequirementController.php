<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalRequirement;
use App\Traits\CustomHelper;
use App\Http\Resources\PersonalRequirementResource;
use App\Models\PersonalRequestApprover;
use App\Models\UserProfileData;
use Illuminate\Support\Facades\DB;

class PersonalRequirementController extends Controller
{
    use CustomHelper;

    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 10;

        $auth_user_profile = UserProfileData::where('user_id', $this->getAuthUserId($request))->first();
        $personal_requirements = PersonalRequirement::where('approver_id', $auth_user_profile?->id)->search($request->search)
            ->paginate($per_page);

        return response()->json([
            'data' => PersonalRequirementResource::collection($personal_requirements),
            'links' => $personal_requirements->links(),
            'current_page' => $personal_requirements->currentPage(),
            'from' => $personal_requirements->firstItem(),
            'last_page' => $personal_requirements->lastPage(),
            'path' => request()->url(),
            'per_page' => $personal_requirements->perPage(),
            'to' => $personal_requirements->lastItem(),
            'total' => $personal_requirements->total(),
        ]);
    }

    public function setStatus(Request $request)
    {
        try {
            $request->validate([
                "id" => 'required|integer',
                'status' => 'required|string|in:approved,rejected'
            ]);

            DB::transaction(function () use ($request) {
                $personal_requirement = PersonalRequirement::findOrFail($request->id);
                $personal_requirement->status = $request->status;
                $personal_requirement->save();

                if ($personal_requirement->status == 'approved' && $personal_requirement->approver?->personalRequestApprover?->type == 'manager') {
                    $management = PersonalRequestApprover::where('type', 'management')->get();
                    foreach ($management as $manager) {
                        $personal_requirement->personalRequest->personalRequirements()->create([
                            'status' => 'pending',
                            'approver_id' => $manager->approver_id
                        ]);
                    }
                } else if (
                    $personal_requirement->status == 'approved' &&
                    $personal_requirement->approver?->personalRequestApprover?->type == 'management'
                    && ($personal_requirement->personalRequest->personalRequirements()->count() == $personal_requirement->personalRequest->personalRequirements()->where('status', 'approved')->count())
                ) {
                    $management = PersonalRequestApprover::where('type', 'executive-management')->get();
                    foreach ($management as $manager) {
                        $personal_requirement->personalRequest->personalRequirements()->create([
                            'status' => 'pending',
                            'approver_id' => $manager->approver_id
                        ]);
                    }
                }
            });
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ], 500);
        }

        return response()->json([
            "success" => true,
            "message" => "Status updated successfully"
        ], 200);
    }
}
