<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalRequestApprover;

class PersonalRequestApproverController extends Controller
{
    /**
     * list the specified resorces
     */
    public function index()
    {
        $personal_request_approver = new PersonalRequestApprover();
        return response()->json([
            'manager' => $personal_request_approver->manager()?->approver ? [
                'id' => $personal_request_approver->manager()?->approver?->id,
                'userId' => $personal_request_approver->manager()?->approver?->user_id,
                'employee' => $personal_request_approver->manager()?->approver?->full_name
            ] : null,
            'management' => $personal_request_approver->management()->map(function ($manager) {
                return [
                    'id' => $manager?->approver?->id,
                    'userId' => $manager?->approver?->user_id,
                    'employee' => $manager?->approver?->full_name
                ];
            }),
            'executiveManagement' => $personal_request_approver->executiveManagement()->map(function ($manager) {
                return [
                    'id' => $manager?->approver?->id,
                    'userId' => $manager?->approver?->user_id,
                    'employee' => $manager?->approver?->full_name
                ];
            }),
        ]);
    }

    /**
     * save the specified resource
     */
    public function saveData(Request $request)
    {
        $request->validate([
            'manager' => 'required|integer',
            'management' => 'required|array',
            'management.*' => 'required|integer',
            'executiveManagement' => 'required|array',
            'executiveManagement.*' => 'required|integer',
        ]);

        PersonalRequestApprover::where('type', 'manager')->delete();
        PersonalRequestApprover::create([
            'approver_id' => $request->manager,
            'type' => 'manager'
        ]);

        PersonalRequestApprover::where('type', 'management')->delete();
        foreach ($request->management as $manager) {
            PersonalRequestApprover::create([
                'approver_id' => $manager,
                'type' => 'management'
            ]);
        }

        PersonalRequestApprover::where('type', 'executive-management')->delete();
        foreach ($request->executiveManagement as $manager) {
            PersonalRequestApprover::create([
                'approver_id' => $manager,
                'type' => 'executive-management'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Approvers saved successfully'
        ], 200);
    }
}
