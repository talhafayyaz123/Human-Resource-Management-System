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
        Schema::create('user_compensation_data', function (Blueprint $table) {
            $table->id();

            //Compensation
            $table->string("compensation_type")->nullable();
            $table->string("gross_monthly_salary")->nullable();
            $table->string("gross_hourly_salary")->nullable();

            //Benefits

            //Pension plan
            $table->string("contract_number")->nullable();
            $table->string("insurance_company")->nullable();
            $table->string("amount_monthly")->nullable();

            $table->unsignedBigInteger('user_profile_id');

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
        Schema::dropIfExists('user_compensation_data');
    }
};
