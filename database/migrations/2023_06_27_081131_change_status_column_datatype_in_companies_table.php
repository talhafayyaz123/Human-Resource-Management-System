<?php

use App\Models\Company;
use App\Models\LeadStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'lead_status_id')) {
                $table->foreignId('lead_status_id')->nullable()->after('terms_of_payment')->constrained('lead_statuses')->cascadeOnDelete()->cascadeOnUpdate();
            }
        });

        //check if the Company have already data exist
        $companies = Company::all();

        if($companies){
            //Add default entry to replace live data with their ids.
            $open = LeadStatus::firstOrCreate(['name' => 'Open'], ['name' => 'Open']);
            $closed = LeadStatus::firstOrCreate([ 'name' => 'Closed'], [ 'name' => 'Closed']);

            foreach ($companies as $row_sys) {
                if (!empty($row_sys->status)) {
                    $row_sys->lead_status_id = $row_sys->status === 'open' ? $open->id : ($row_sys->status === 'closed' ? $closed->id : '');
                    $row_sys->save();
                }
            }
        }

        // Also delete previous column of operating system
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'status')) {
                $table->dropColumn('status');
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
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(["lead_status_id"]);
            $table->dropColumn(["lead_status_id"]);
        });
    }
};
