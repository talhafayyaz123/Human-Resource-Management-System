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
        Schema::create('time_tracker_user_data', function (Blueprint $table) {
            $table->id();

            $table->date('monthly_start_date');
            $table->date('monthly_end_date');
            $table->string('actual_worked_hours')->default(0);
            $table->integer('actual_working_days')->default(0);
            $table->float('total_holidays_taken')->default(0);

            $table->string('user_id')->nullable();
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
        Schema::dropIfExists('time_tracker_user_data');
    }
};
