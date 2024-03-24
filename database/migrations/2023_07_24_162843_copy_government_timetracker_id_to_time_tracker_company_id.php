<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_tracker_company_id', function (Blueprint $table) {
            $companies = DB::table('time_tracker_companies')->select('id', 'government_timetracker_id')->get();

            foreach ($companies as $company) {
                $government = DB::table('time_tracker_governments')
                    ->where('id', $company->government_timetracker_id)
                    ->first();
    
                if ($government) {
                    DB::table('time_tracker_governments')
                        ->where('id', $company->government_timetracker_id)
                        ->update(['time_tracker_company_id' => $company->id]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_tracker_company_id', function (Blueprint $table) {
            //
        });
    }
};
