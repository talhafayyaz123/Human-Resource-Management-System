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
        Schema::table('user_job_data', function (Blueprint $table) {
            $table->integer('probation_period_months')->nullable();
            $table->timestamp('probation_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_job_data', function (Blueprint $table) {
            $table->dropColumn('probation_end_date');
            $table->dropColumn('probation_period_months');
        });
    }
};
