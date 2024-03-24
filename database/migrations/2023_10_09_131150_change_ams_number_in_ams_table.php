<?php

use App\Models\ApplicationManagementService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $application_management_services = ApplicationManagementService::all();
        foreach ($application_management_services as $application_management_service) {
            $application_management_service->ams_number = 'AMS-' . $application_management_service->ams_number;
            $application_management_service->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_management_services', function (Blueprint $table) {
            //
        });
    }
};
