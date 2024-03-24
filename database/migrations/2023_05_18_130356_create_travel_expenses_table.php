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
        Schema::create('travel_expenses', function (Blueprint $table) {
            $table->id();

            //Departure data
            $table->string('departure_city');
            $table->string('departure_country');
            $table->string('arrival_city');
            $table->string('arrival_country');

            $table->date('from_date');
            $table->string('from_departure_time');
            $table->string('from_arrival_time');
            $table->string('from_discrepancy');
            
            $table->date('to_date');
            $table->string('to_departure_time');
            $table->string('to_arrival_time');
            $table->string('to_discrepancy');

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
        Schema::dropIfExists('travel_expenses');
    }
};
