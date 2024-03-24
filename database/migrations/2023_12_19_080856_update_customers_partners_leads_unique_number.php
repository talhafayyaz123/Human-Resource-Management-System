<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies = \App\Models\Company::all();
        $customers = $companies->where('customer_type', 'customer');
        $count = 1000;
        foreach ($customers as $customer) {
            $customer->company_number = 'C' .$count;
            $customer->save();
            $count++;
        }
        \App\Models\GlobalSetting::where('key', 'company')->update(['value' => $count]);
        $count = 1000;
        $leads = $companies->where('customer_type', 'lead');
        foreach ($leads as $lead) {
            $lead->company_number = 'L' .$count;
            $lead->save();
            $count++;
        }
        \App\Models\GlobalSetting::where('key', 'lead')->update(['value' => $count]);
        $count = 1000;
        $partners = $companies->where('customer_type', 'partner');
        foreach ($partners as $partner) {
            $partner->company_number = 'P' .$count;
            $partner->save();
            $count++;
        }
        \App\Models\GlobalSetting::where('key', 'partner')->update(['value' => $count]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
