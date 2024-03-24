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
        Schema::table('travel_expenses', function (Blueprint $table) {
            $table->after('user_id', function ($table) {
                $table->unsignedBigInteger('departure_country_id')->nullable();
                $table->foreign('departure_country_id')->references('id')->on('countries')->onDelete('SET NULL');

                $table->unsignedBigInteger('arrival_country_id')->nullable();
                $table->foreign('arrival_country_id')->references('id')->on('countries')->onDelete('SET NULL');
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
        Schema::table('travel_expenses', function (Blueprint $table) {
            // Remove foreign key constraint
            $table->dropForeign(['departure_country_id']);
            $table->dropForeign(['arrival_country_id']);

            // Remove the country_id column
            $table->dropColumn('departure_country_id');
            $table->dropColumn('arrival_country_id');
        });
    }
};
