<?php

namespace App\Http\Controllers;

use App\Models\ConsultingTeam;
use App\Http\Requests\ConsultingTeamRequest;
use App\Http\Resources\ConsultingTeamResource;

class ConsultingTeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:consulting-teams.list', ['only' => ['index']]);
        $this->middleware('check.permission:consulting-teams.save', ['only' => ['saveData']]);
    }

    /**
     * returns consulting teams listing
     */
    public function index()
    {
        $teams = ConsultingTeam::with('team')->get();
        return ConsultingTeamResource::collection($teams);
    }

    /**
     * saves/updates the consulting teams
     * @param ConsultingTeamRequest $request
     * @return JSONResponse
     */
    public function saveData(ConsultingTeamRequest $request)
    {
        $request->validated();
        $teams = collect($request->teams ?? []);
        // loop over teams from request
        foreach ($teams as $team) {
            // if a team does not exist in consulting_teams then create a consulting team record
            if (!ConsultingTeam::where('team_id', $team['id'])->first()) {
                ConsultingTeam::create([
                    'team_id' => $team['id']
                ]);
            }
        }
        // remove all consulting teams that are not part of the request
        ConsultingTeam::whereNotIn('team_id', $teams->map(function ($team) {
            return $team['id'];
        }))->delete();
        return response()->json([
            "success" => true,
            "message" => "Record saved successfully"
        ]);
    }
}
