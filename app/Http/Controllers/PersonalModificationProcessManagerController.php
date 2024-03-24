<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalModificationProcessManager;

class PersonalModificationProcessManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PersonalModificationProcessManager::get()->map(function ($manager) {
            return [
                'id' => $manager->user?->id,
                'employee' => ($manager->user?->first_name ?? '') . ' ' . ($manager->user?->last_name ?? ''),
                'userId' => $manager->user?->userId,
            ];
        });
    }

    public function saveData(Request $request)
    {
        $request->validate([
            'managers' => 'array|required',
            'managers.*' => 'integer|required'
        ]);

        PersonalModificationProcessManager::all()->each->delete();

        foreach ($request->managers as $manager) {
            PersonalModificationProcessManager::create(["manager_id" => $manager]);
        }

        return response()->json([
            "success" => true,
            "message" => "Managers saved successfully"
        ]);
    }
}
