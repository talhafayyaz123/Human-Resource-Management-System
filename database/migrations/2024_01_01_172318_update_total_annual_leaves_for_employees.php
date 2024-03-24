<?php

use Carbon\Carbon;
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
        $subYear = Carbon::now()->subYear()->format('Y');
        $userJobLeaves = \App\Models\UserJobLeave::where('leave_year', $subYear)->get();
        foreach ($userJobLeaves as $leave) {
            \App\Models\UserJobLeave::updateOrCreate([
                'user_job_id' => $leave->user_job_id,
                'leave_year' => \Carbon\Carbon::now()->format('Y'),
            ],[
                'total_annual_leaves' => $leave->total_annual_leaves,
                'additional_leaves' => 0
            ]);
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
