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
            $table->dropColumn(['arrival_country', 'departure_country']);
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
            // Recreate the 'arrival_country' column
            $table->string('arrival_country')->nullable();

            // Recreate the 'departure_country' column
            $table->string('departure_country')->nullable();
        });
    }
};
