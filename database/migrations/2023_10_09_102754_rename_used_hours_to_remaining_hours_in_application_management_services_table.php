<?php

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
        Schema::table('application_management_services', function (Blueprint $table) {
            if(Schema::hasColumn('application_management_services', 'used_hours')){
                $table->renameColumn('used_hours', 'remaining_hours');
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
        Schema::table('application_management_services', function (Blueprint $table) {
            if(Schema::hasColumn('application_management_services', 'remaining_hours')){
                $table->renameColumn('remaining_hours', 'used_hours');
            }
        });
    }
};
