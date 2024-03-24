<?php

namespace App\Http\Controllers;

use App\Models\FleetManagementIntervalSetting;
use Exception;
use Illuminate\Http\Request;

class FleetManagementIntervalSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interval_settings = FleetManagementIntervalSetting::with('managers')->get();
        return response()->json(
            ['intervalSettings'
            => $interval_settings]
        );
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
            'months' => 'required|integer',
            'managers' => 'required|array',
            'type' => 'required|in:licence,uvv,fuel,cost'
        ]);
        if (FleetManagementIntervalSetting::where('interval_setting_type', $request->type)->exists()) {
            return response()->json(['message' => 'An entry with this type already exists'], 422);
        }
        $interval_setting = new FleetManagementIntervalSetting;
        $interval_setting->months = $request->months;
        $interval_setting->interval_setting_type = $request->type;
        $interval_setting->save();
        $managers = [];
        foreach ($request->managers as $manager) {
            $managers[] = ['manager_id' => $manager['id']];
        }
        $interval_setting->managers()->createMany($managers);
        return response()->json(['message' => 'The interval setting has been created'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $interval_setting = FleetManagementIntervalSetting::with('managers')->findOrFail($id);
        return response()->json(['intervalSetting'
        => $interval_setting]);
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
            'months' => 'required|integer',
            'managers' => 'required|array'
        ]);
        if (FleetManagementIntervalSetting::where('interval_setting_type', $request->type)->whereNotIn('id', [$id])->exists()) {
            return response()->json(['message' => 'An entry with this type already exists'], 422);
        }
        $interval_setting = FleetManagementIntervalSetting::findOrFail($id);
        $interval_setting->update([
            'months' => $request->months,
            'interval_setting_type' => $request->type
        ]);
        if ($interval_setting->managers) {
            $interval_setting->managers()->delete();
        }
        $managers = [];
        foreach ($request->managers as $manager) {
            $managers[] = ['manager_id' => $manager['id']];
        }
        $interval_setting->managers()->createMany($managers);

        return response()->json(['message' => 'The interval setting has been updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interval_setting = FleetManagementIntervalSetting::findOrFail($id);
        if ($interval_setting->managers) {
            $interval_setting->managers()->delete();
        }
        $interval_setting->delete();

        return response()->json(['message' => 'The interval setting has been deleted']);
    }
}
