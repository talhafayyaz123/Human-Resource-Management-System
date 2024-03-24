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
        Schema::table('user_profile_data', function (Blueprint $table) {
            $table->after('user_id', function ($table) {
                $table->unsignedBigInteger('highest_school_degree_id')->nullable();
                $table->foreign('highest_school_degree_id')->references('id')->on('highest_school_degrees')->nullable()->onDelete('set null');
                $table->unsignedBigInteger('highest_education_level_id')->nullable();
                $table->foreign('highest_education_level_id')->references('id')->on('highest_education_levels')->nullable()->onDelete('set null');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profile_data', function (Blueprint $table) {
            $table->dropForeign(['highest_school_degree_id']);
            $table->dropColumn('highest_school_degree_id');
            $table->dropForeign(['highest_education_level_id']);
            $table->dropColumn('highest_education_level_id');
        });
    }
};
