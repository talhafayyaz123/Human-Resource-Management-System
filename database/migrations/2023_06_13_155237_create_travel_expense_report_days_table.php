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
        Schema::create('travel_expense_report_days', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_active')->default(0);
            $table->date('date')->nullable();
            $table->boolean('breakfast')->default(0);
            $table->boolean('lunch')->default(0);
            $table->boolean('dinner')->default(0);
            $table->time('from_time')->nullable();
            $table->time('to_time')->nullable();

            $table->unsignedBigInteger('travel_expense_id')->nullable()->references('id')->on('travel_expenses')->onDelete('cascade');
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
        Schema::dropIfExists('travel_expense_report_days');
    }
};
