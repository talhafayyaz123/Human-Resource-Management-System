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
        Schema::create('user_job_data', function (Blueprint $table) {
            $table->id();

            //Job data
            $table->string("job_title")->nullable();
            $table->string("job_description")->nullable();
            $table->string("personal_number")->nullable();
            $table->string("job_number")->nullable();

            $table->string("contract_type")->nullable();
            $table->string("supervisor_id")->nullable();
            $table->date("join_date")->nullable();
            $table->date("exit_date")->nullable();
            $table->date('accounting_date')->nullable();
            $table->boolean("is_main_job")->nullable()->default(null);
            $table->integer("cost_center")->nullable();
            $table->integer("is_employee_leasing")->nullable();

            //Assigned Workspace
            $table->string("default_workspace")->nullable();

            //Working hours
            $table->json("working_days")->nullable();
            $table->integer("weekly_hours")->nullable();
            $table->integer("total_annual_leaves")->nullable();
            $table->integer("remaining_leaves")->nullable();

            $table->unsignedBigInteger('user_profile_id');
            $table->string('user_location_id')->nullable();

            $table->foreign('user_profile_id')->references('id')->on('user_profile_data')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_job_data');
    }
};
