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
        Schema::table('time_tracker_governments', function (Blueprint $table) {
            $table->unsignedBigInteger('time_tracker_company_id')->nullable();
            $table->foreign('time_tracker_company_id')->references('id')->on('time_tracker_companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_tracker_governments', function (Blueprint $table) {
            $table->dropForeign(['time_tracker_company_id']);
            $table->dropColumn('time_tracker_company_id');
        });
    }
};
