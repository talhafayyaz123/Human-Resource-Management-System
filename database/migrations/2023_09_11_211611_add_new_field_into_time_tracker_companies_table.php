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
        Schema::table('time_tracker_companies', function (Blueprint $table) {
            if (!Schema::hasColumn('time_tracker_companies', 'time_checking_status')) {
                $table->boolean('time_checking_status')->nullable();
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
        Schema::table('time_tracker_companies', function (Blueprint $table) {
            if (Schema::hasColumn('time_tracker_companies', 'time_checking_status')) {
                $table->dropColumn('time_checking_status');
            }
        });
    }
};
