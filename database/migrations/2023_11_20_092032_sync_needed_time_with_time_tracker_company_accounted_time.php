<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\TimeTrackerCompany;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            DB::transaction(function () {
                // get all TimeTrackerCompany non goodwill task records 
                $time_tracker_company_non_goodwill = TimeTrackerCompany::where('module_type', 'App\Models\Task')->where('is_goodwill', false)->get();
                // get all TimeTrackerCompany goodwill task records 
                $time_tracker_company_goodwill = TimeTrackerCompany::where('module_type', 'App\Models\Task')->where('is_goodwill', true)->get();

                // loop over $time_tracker_company_non_goodwill and update the project's needed_time
                foreach ($time_tracker_company_non_goodwill as $entry) {
                    $needed_time = (float) $entry->module->milestone->project->needed_time;
                    $accounted_time = (float) $entry->time;
                    $entry->module->milestone->project->needed_time = $needed_time + $accounted_time;
                    $entry->module->milestone->project->save();
                }

                // loop over $time_tracker_company_goodwill and update the project's needed_time_with_goodwill
                foreach ($time_tracker_company_goodwill as $entry) {
                    $needed_time_with_goodwill = (float) $entry->module->milestone->project->needed_time_with_goodwill;
                    $accounted_time = (float) $entry->time;
                    $entry->module->milestone->project->needed_time_with_goodwill = $needed_time_with_goodwill + $accounted_time;
                    $entry->module->milestone->project->save();
                }
            });
        } catch (\Exception $e) {
        }
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
