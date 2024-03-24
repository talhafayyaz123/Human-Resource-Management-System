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
        Schema::table('compensation_bonuses', function (Blueprint $table) {
            $table->enum('goal', ['ticket_solution_rate', 'customer_satisfaction_rate', 'cross_selling_year'])->nullable();
            $table->string('percent_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compensation_bonuses', function (Blueprint $table) {
            //
        });
    }
};
