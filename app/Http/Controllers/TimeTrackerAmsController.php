<?php

namespace App\Http\Controllers;

use App\Models\ApplicationManagementService;
use App\Models\UserProfileData;
use Illuminate\Http\Request;

class TimeTrackerAmsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'amsId' => 'required|exists:application_management_services,id'
        ]);
        $ams = ApplicationManagementService::findOrFail($request->amsId);
        $time_tracker_companies = $ams->timeTrackerCompanys()->get();
        $time_tracker_companies = $time_tracker_companies?->map(function ($item) {
            $username = UserProfileData::where('user_id', $item->user_id)?->selectRaw('CONCAT(first_name, " ", last_name) AS name')->first();
            return  [
                "id" => $item->id,
                "date" => $item->date,
                "type" => 'ams',
                "moduleId" => $item->module_id,
                "userId" => $item->user_id,
                "username" => $username?->name,
                "description" => $item->description,
                'company' => $item->company->company_name,
                "quantity" => $item->time,
                "isGoodwill" => $item->is_goodwill == 1
            ];
        });
        return response()->json(['data' => $time_tracker_companies]);
    }
}
