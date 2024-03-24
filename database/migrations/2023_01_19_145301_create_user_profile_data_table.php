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
        Schema::create('user_profile_data', function (Blueprint $table) {
            $table->id();

            //Personal data
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('public_phone')->nullable();
            $table->string('birth_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('country_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('highest_school_degree')->nullable();
            $table->string('highest_education_level')->nullable();

            //Private address
            $table->string('street')->nullable();
            $table->string('address')->nullable();
            $table->string('secondary_address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();

            //Contact Details
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email');

            //Children
            $table->json('children_data')->nullable();

            //Tax data
            $table->string('finance_department_number')->nullable();
            $table->integer('finance_category')->nullable();
            $table->string('religion')->nullable();
            $table->string('religion_of_partner')->nullable();
            $table->integer('free_amount_children')->nullable();
            $table->integer('free_amount_yearly')->nullable();
            $table->integer('free_amount_monthly')->nullable();
            $table->string('tax_liability')->nullable();

            $table->string('private_email')->nullable();
            //Bank details
            $table->string("account_owner")->nullable();
            $table->string("iban")->nullable();
            $table->string("bic")->nullable();
            $table->string("bank_name")->nullable();

            //Social security number
            $table->string("social_security_number")->nullable();
            $table->string("health_insurance")->nullable();
            $table->string("previous_health_insurance")->nullable();
            $table->integer("group_people")->nullable();
            $table->integer("contribution_group")->nullable();

            //Insurance
            $table->string("accident_insurance")->nullable();
            $table->string("tariff_points")->nullable();
            $table->string("percentage_association")->nullable();

            $table->string("user_id")->nullable();
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
        Schema::dropIfExists('user_profile_data');
    }
};
