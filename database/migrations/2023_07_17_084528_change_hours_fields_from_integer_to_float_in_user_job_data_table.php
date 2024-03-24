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
            $table->decimal("weekly_hours")->change();
            $table->decimal("total_annual_leaves")->change();
            $table->decimal("remaining_leaves")->change();
            $table->decimal("remaining_leaves_year")->change();
            $table->decimal("probation_period_months")->change();
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
            $table->integer("weekly_hours")->change();
            $table->integer("total_annual_leaves")->change();
            $table->integer("remaining_leaves")->change();
            $table->integer("remaining_leaves_year")->change();
            $table->integer("probation_period_months")->change();
        });
    }
};
