<?php

namespace App\Services;

use App\Models\ApplicationManagementService;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\DB;

class AMSService
{
    /**
     * Create a new fleet car.
     *
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function createAMS(array $data)
    {
        $global_invoice_setting = GlobalSetting::where('key', 'ams')->first();
        if (empty($global_invoice_setting)) {
            $global_setting = new GlobalSetting();
            $global_setting->key = 'ams';
            $global_setting->value = 100000;
            $global_setting->save();
        } else {
            $global_setting = tap(DB::table('global_settings')->where('key', 'ams'))->update([
                'value' => DB::raw('value+1')
            ])->first();
        }
        $value = 'AMS-' . $global_invoice_setting->value;
        $ams = ApplicationManagementService::create([
            ...$data,
            'ams_number' => $value
        ]);
        return $ams;
    }

    /**
     * Update the specified fleet car.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @param  array  $data
     * @return \App\Models\FleetCar
     */
    public function updateAMS(ApplicationManagementService $ams, array $data)
    {
        $ams->update([
            ...$data,
        ]);
        return $ams;
    }

    /**
     * Delete the specified fleet car.
     *
     * @param  \App\Models\FleetCar  $fleetCar
     * @return void
     */
    public function deleteAMS(ApplicationManagementService $ams)
    {
        $ams->delete();
    }
}
