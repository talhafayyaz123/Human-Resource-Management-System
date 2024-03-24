<?php

namespace App\Traits;

use App\Models\GlobalSetting;
use Illuminate\Support\Facades\DB;

trait SetGlobalNumber
{
    public function globalNumber($model, string $key, string $prefix, string $number, ?string $type = null)
    {
        $global_invoice_setting = GlobalSetting::where('key', $key);

        if ($key == 'performance_record'){
           $global_invoice_setting = $global_invoice_setting->where('year', $prefix)->first();
        }else{
            $global_invoice_setting = $global_invoice_setting->first();
        }

        if (empty($global_invoice_setting)) {
            $global_setting = new GlobalSetting;
            $global_setting->key = $key;
            $global_setting->value = $number;

            if ($key == 'performance_record') {
                $global_setting->year = $prefix;
            }

            $global_setting->save();
        } else {
            if ($key == 'performance_record') {
                $global_setting = tap(DB::table('global_settings')->where('key', $key)->where('year', date("Y")))->update([
                    'value' => DB::raw("value+1")
                ])->first();
            } else {
                $global_setting = tap(DB::table('global_settings')->where('key', $key))->update([
                    'value' => DB::raw("value+1")
                ])->first();
            }

        }
        if ($type == 'customer') {
            $number = $global_setting->value;
        } else if ($type == 'ticket') {
            $number = $prefix . $global_setting->value;
        } else {
            $number = $prefix . '-' . $global_setting->value;
        }

        return $number;
    }
}
